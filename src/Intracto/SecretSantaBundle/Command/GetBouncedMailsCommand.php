<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetBouncedMailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('intracto:getBounced')
            ->setDescription('Get bounced emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bounceQuery = $this->getContainer()->get('intracto_secret_santa.query.bounce');
        $bounces = $bounceQuery->getBounces();
        foreach ($bounces as $bounce) {
            $date = new \DateTime($bounce['date']);
            $id = $bounceQuery->findBouncedParticipantId($bounce['email'], $date);
            if ($id) {
                $id = (int) $id;
                $bounceQuery->markParticipantEmailAsBounced($id);
            }
            $bounceQuery->removeBounce($bounce['id']);
        }
    }
}
