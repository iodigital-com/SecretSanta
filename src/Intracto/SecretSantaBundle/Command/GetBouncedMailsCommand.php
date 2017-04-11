<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class GetBouncedMailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('intracto:getbounced')
            ->setDescription('Get bounced emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        // TODO: PROVIDE INPUT FOR BOUNCE COMMAND!
        $input = '';

        preg_match_all('~<(.*@.*)>~', $input, $emails);
        foreach ($emails[1] as $email) {
            $query = $em->createQuery('
                SELECT participant
                FROM IntractoSecretSantaBundle:Participant participant
                WHERE participant.email = :email
            ')->setParameter('email', $email);
            $participants = $query->getResult();

            /** Participant $participant */
            foreach ($participants as $participant) {
                $participant->setEmailDidBounce(true);
                $em->persist($participant);
            }
        }
        $em->flush();
    }
}
