<?php

namespace Intracto\SecretSantaBundle\Mailer\Mail;

use Doctrine\ORM\EntityManager;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface for emails sent in batch to Pool Entries.
 */
interface BatchEntryMail
{

    public function __construct($batchSize);

    /**
     * @return string
     */
    public function getName();

    /**
     * Returns the list of entries to send the batch mail to.
     *
     * @param EntityManager $entityManager
     *
     * @return Entry[]
     */
    public function getReceiverEntries(EntityManager $entityManager);

    /**
     * Returns a translatable string that will be used as the email subject.
     *
     * @param Entry               $receiver
     * @param TranslatorInterface $translator
     *
     * @return string
     */
    public function getSubject(Entry $receiver, TranslatorInterface $translator);

    /**
     * @param Entry               $receiver
     * @param TranslatorInterface $translator
     *
     * @return string
     */
    public function getFrom(Entry $receiver, TranslatorInterface $translator);

    /**
     * @param Entry $receiver
     *
     * @return string
     */
    public function getPlainTextTemplate(Entry $receiver);

    /**
     * @param Entry $receiver
     *
     * @return string
     */
    public function getHtmlTemplate(Entry $receiver);

    /**
     * @param Entry         $receiver
     * @param EntityManager $entityManager
     *
     * @return array
     */
    public function getTemplateData(Entry $receiver, EntityManager $entityManager);

    /**
     * Called when an email has been sent.
     *
     * @param Entry         $receiver
     * @param EntityManager $em
     */
    public function handleMailSent(Entry $receiver, EntityManager $em);
}
