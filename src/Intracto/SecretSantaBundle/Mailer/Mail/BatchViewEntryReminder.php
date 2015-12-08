<?php

namespace Intracto\SecretSantaBundle\Mailer\Mail;

use Doctrine\ORM\EntityManager;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Mail sent to people who haven't seen their gift buddy yet.
 *
 * Sent every two weeks, first one six weeks before the party date.
 */
class BatchViewEntryReminder implements BatchEntryMail
{

    private $batchSize;

    /**
     * BatchViewEntryReminder constructor.
     */
    public function __construct($batchSize)
    {
        $this->batchSize = $batchSize;
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'View gift buddy reminder';
    }

    /**
     * {@inheritdoc}
     */
    public function getReceiverEntries(EntityManager $entityManager)
    {
        /**
         * @var $entryRepository \Intracto\SecretSantaBundle\Entity\EntryRepository
         */
        $entryRepository = $entityManager->getRepository('IntractoSecretSantaBundle:Entry');

        return $entryRepository->findBatchToRemindToViewEntry($this->batchSize);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(Entry $receiver, TranslatorInterface $translator)
    {
        $translator->setLocale($receiver->getPool()->getLocale());

        return $translator->trans('emails.viewentryreminder.subject');
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom(Entry $receiver, TranslatorInterface $translator)
    {
        $translator->setLocale($receiver->getPool()->getLocale());

        return $translator->trans('emails.sender');
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainTextTemplate(Entry $receiver)
    {
        return 'IntractoSecretSantaBundle:Emails:viewentryreminder.txt.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getHtmlTemplate(Entry $receiver)
    {
        return 'IntractoSecretSantaBundle:Emails:viewentryreminder.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateData(Entry $receiver, EntityManager $entityManager)
    {
        return ['entry' => $receiver];
    }

    /**
     * {@inheritdoc}
     */
    public function handleMailSent(Entry $receiver, EntityManager $em)
    {
        $receiver->setViewReminderSentTime(new \DateTime());

        $em->persist($receiver);
        $em->flush();
    }
}
