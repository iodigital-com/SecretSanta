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
     * @DI\Inject("%admin_email%")
     */
    public $adminEmail = 'test@example.com';

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
        /**
         * @var $qb QueryBuilder
         */
        $qb = $em->getRepository("IntractoSecretSantaBundle:Entry")
            ->createQueryBuilder('IntractoSecretSantaBundle:Entry');
        $entries = $qb->select('e', 'ss.email')
            ->from('IntractoSecretSantaBundle:Entry', 'e')->innerJoin("e.entry", 'ss')
            ->where($qb->expr()->eq("e.wishlist_updated", '1'))
            ->groupBy('e.id')
            ->getQuery()->getResult();

        foreach ($entries as $entryline) {
            $entryEntity = $entryline[0];
            $ss_email = $entryline['email'];
            $message = \Swift_Message::newInstance()
                ->setSubject('Secret Santa Confirmation')
                ->setFrom($this->adminEmail, "Santa Claus")
                ->setTo($ss_email)
                ->setBody(
                    $this->getContainer()->get('templating')->render(
                        'IntractoSecretSantaBundle:Emails:wishlist_changed.html.twig',
                        array(
                            'entry' => $entryEntity,
                            'ss_email' => $ss_email,
                        )
                    )
                );
            if ($input->getArgument('force')) {
                $this->getContainer()->get('mailer')->send($message);
                $entryEntity->setWishlistUpdated(false);
                $em->flush($entryEntity);


            }
        }
    }
}