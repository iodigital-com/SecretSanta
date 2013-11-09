<?php

namespace Intracto\SecretSantaBundle\Command;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;


class MailqueueCommand extends ContainerAwareCommand
{

    /**
     * Configure the command options
     */
    protected function configure()
    {
        $this
          ->setName('intracto:sendmails')
          ->setDescription('Process the MailQueue')
          ->addArgument(
              'force',
              null,
              'If not set, a trial run will execute. No mails will be actually sent',
              false
          );
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|voidSp
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost($this->getContainer()->getParameter("base_url"));
//        $context->setScheme('http');
        /**
         * @var $qb QueryBuilder
         */
        $qb = $em->getRepository("IntractoSecretSantaBundle:Entry")
          ->createQueryBuilder('IntractoSecretSantaBundle:Entry');
        $secret_santas = $qb->select('secret_santa')
          ->from('IntractoSecretSantaBundle:Entry', 'secret_santa')->innerJoin("secret_santa.entry", 'receiver')
          ->where($qb->expr()->eq("receiver.wishlist_updated", '1'))
          ->getQuery()->getResult();

        foreach ($secret_santas as $secret_santa) {

            $receiver =  $secret_santa->getEntry();
            $message = \Swift_Message::newInstance()
              ->setSubject('Wishlist updated')
              ->setFrom($this->getContainer()->getParameter("admin_email"), "Santa Claus")
              ->setTo($secret_santa->getEmail())
              ->setBody(
                  $this->getContainer()->get('templating')->render(
                      'IntractoSecretSantaBundle:Emails:wishlistchanged.txt.twig',
                      array(
                          'entry' => $receiver,
                          'secret_santa' => $secret_santa,
                      )
                  )
              )
              ->addPart(
                  $this->getContainer()->get('templating')->render(
                      'IntractoSecretSantaBundle:Emails:wishlistchanged.html.twig',
                      array(
                          'entry' => $receiver,
                          'secret_santa' => $secret_santa,
                      )
                  ),
                  'text/html'
              );

            if ($input->getArgument('force')) {


                $this->getContainer()->get('mailer')->send($message);
                $receiver->setWishlistUpdated(false);
                $em->flush($receiver);


            }
        }
        $container = $this->getContainer();
        $mailer = $container->get('mailer');
        $spool = $mailer->getTransport()->getSpool();
        $transport = $container->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);
    }
}