<?php

namespace Intracto\SecretSantaBundle\Mailer\Mail;

use Doctrine\ORM\EntityManager;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\Translation\Translator;

/**
 * Mail sent to people who haven't seen their gift buddy yet.
 *
 * Sent every two weeks, first one six weeks before the party date.
 */
class BatchViewEntryReminder implements BatchEntryMail
{

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

        return $entryRepository->findAllToRemindToViewEntry();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(Entry $receiver, Translator $translator)
    {
        $translator->setLocale($receiver->getPool()->getLocale());

        return $translator->trans('emails.viewentryreminder.subject');
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom(Entry $receiver, Translator $translator)
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
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function handleMailSent(Entry $receiver, EntityManager $em)
    {
        // TODO: Set reminder sent timestamp
    }

}
