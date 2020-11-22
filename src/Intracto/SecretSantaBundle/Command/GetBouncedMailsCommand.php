<?php

namespace Intracto\SecretSantaBundle\Command;

use Intracto\SecretSantaBundle\Query\BounceQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetBouncedMailsCommand extends Command
{
    private BounceQuery $bounceQuery;

    public function __construct(BounceQuery $bounceQuery)
    {
        $this->bounceQuery = $bounceQuery;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('intracto:getBounced')
            ->setDescription('Get bounced emails');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bounces = $this->bounceQuery->getBounces();
        foreach ($bounces as $bounce) {
            $date = new \DateTime($bounce['date']);
            $id = $this->bounceQuery->findBouncedParticipantId($bounce['email'], $date);
            if ($id) {
                $id = (int) $id;
                $this->bounceQuery->markParticipantEmailAsBounced($id);
            }
            $this->bounceQuery->removeBounce($bounce['id']);
        }

        return 0;
    }
}
