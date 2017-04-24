<?php

namespace Intracto\SecretSantaBundle\Command;

use Intracto\SecretSantaBundle\Query\BounceQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

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
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $bounces = $this->getContainer()->get('doctrine')->getRepository('IntractoSecretSantaBundle:Bounce')->findAll();
        $participantRepository = $this->getContainer()->get('doctrine')->getRepository('IntractoSecretSantaBundle:Participant');
        foreach ($bounces as $bounce){
            $participant = $participantRepository->findBounced($bounce);
            if (! is_null($participant) ){
                $participant->setEmailDidBounce(true);
                $em->persist($participant);
            }
            $em->remove($bounce);
        }
        $em->flush();
    }
}
