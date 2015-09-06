<?php

namespace Intracto\SecretSantaBundle\Mailer\Mail;

use Doctrine\ORM\EntityManager;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Mail sent to people who haven't added anything to their wish list yet.
 *
 * Sent every two weeks, first one six weeks before the party date.
 */
class BatchEmptyWishlistReminder implements BatchEntryMail
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Wishlist still empty reminder';
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

        return $entryRepository->findAllToRemindOfEmptyWishlist();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(Entry $receiver, TranslatorInterface $translator)
    {
        $translator->setLocale($receiver->getPool()->getLocale());

        return $translator->trans('emails.emptywishlistreminder.subject');
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
        return 'IntractoSecretSantaBundle:Emails:emptywishlistreminder.txt.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getHtmlTemplate(Entry $receiver)
    {
        return 'IntractoSecretSantaBundle:Emails:emptywishlistreminder.html.twig';
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
        $receiver->setUpdateWishlistReminderSentTime(new \DateTime());

        $em->persist($receiver);
        $em->flush();
    }
}
