<?php

namespace Intracto\SecretSantaBundle\Mailer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Intracto\SecretSantaBundle\Entity\Entry;
use Intracto\SecretSantaBundle\Entity\Pool;

class MailerService
{
    /** @var \Swift_Mailer */
    public $mailer;
    /** @var \Swift_Mailer */
    public $mandrill;
    /** @var EntityManager */
    public $em;
    /** @var EngineInterface */
    public $templating;
    /** @var  TranslatorInterface */
    public $translator;
    /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router */
    public $routing;
    public $noreplyEmail;

    /**
     * @param \Swift_Mailer       $mailer a regular SMTP mailer, bad monitoring, cheap
     * @param \Swift_Mailer       $mandrill for important mails only, good monitoring, not cheap
     * @param EntityManager       $em
     * @param EngineInterface     $templating
     * @param TranslatorInterface $translator
     * @param Router              $routing
     * @param $noreplyEmail
     */
    public function __construct(
        \Swift_Mailer $mailer,
        \Swift_Mailer $mandrill,
        EntityManager $em,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Router $routing,
        $noreplyEmail
    ) {
        $this->mailer = $mailer;
        $this->mandrill = $mandrill;
        $this->em = $em;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->routing = $routing;
        $this->noreplyEmail = $noreplyEmail;
    }

    /**
     * @param Pool $pool
     */
    public function sendPendingConfirmationMail(Pool $pool)
    {
        $this->translator->setLocale($pool->getLocale());

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-pendingConfirmation.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($pool->getOwnerEmail())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pendingConfirmation.txt.twig',
                    [ 'pool' => $pool ]
                )
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pendingConfirmation.html.twig',
                    [ 'pool' => $pool ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * Sends out all mails for a Pool.
     *
     * @param Pool $pool
     */
    public function sendSecretSantaMailsForPool(Pool $pool)
    {
        $pool->setSentdate(new \DateTime('now'));
        $this->em->flush($pool);

        foreach ($pool->getEntries() as $entry) {
            $this->sendSecretSantaMailForEntry($entry);
        }
    }

    /**
     * Sends out mail for a Entry.
     *
     * @param Entry $entry
     */
    public function sendSecretSantaMailForEntry(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());

        $message = $entry->getPool()->getMessage();
        $message = str_replace('(NAME)', $entry->getName(), $message);
        $message = str_replace('(ADMINISTRATOR)', $entry->getPool()->getOwnerName(), $message);

        $mail = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-participant.subject'))
            ->setFrom($this->noreplyEmail, $entry->getPool()->getOwnerName())
            ->setReplyTo([$entry->getPool()->getOwnerEmail() => $entry->getPool()->getOwnerName()])
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:participant.html.twig',
                    [
                        'message' => $message,
                        'entry' => $entry,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:participant.txt.twig',
                    [
                        'message' => $message,
                        'entry' => $entry,
                    ]
                ),
                'text/plain'
            );
        if (strpos($entry->getEmail(), '@hotmail.') === false) {
            $this->mailer->send($mail);
        } else {
            $this->mandrill->send($mail);
        }
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function sendForgotManageLinkMail($email)
    {
        $results = $this->em->getRepository('IntractoSecretSantaBundle:Pool')->findAllAdminPools($email);

        if (count($results) == 0) {
            return false;
        }

        $poolLinks = [];
        foreach ($results as $result) {
            $text = $this->translator->trans('emails-forgot_link.title');

            if ($result['eventdate'] instanceof \DateTime) {
                $text .= ' ('.$result['eventdate']->format('d/m/Y').')';
            }

            $poolLinks[] = [
                'url' => $this->routing->generate('pool_manage', ['listUrl' => $result['listurl']], Router::ABSOLUTE_URL),
                'text' => $text,
            ];
        }

        $this->translator->setLocale($results[0]['locale']);

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-forgot_link.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:forgotLink.html.twig',
                    [
                        'poolLinks' => $poolLinks,
                    ]
                ),
                'text/html'
            )
            ->addPart(

                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:forgotlink.txt.twig',
                    [
                        'poolLinks' => $poolLinks,
                    ]
                ),
                'text/plain'
            );
        $this->mailer->send($message);

        return true;
    }

    /**
     * @param Entry $entry
     */
    public function sendPokeMailToBuddy(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-pokeBuddy.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pokeBuddy.html.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pokeBuddy.txt.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Pool $pool
     * @param $results
     */
    public function sendPoolUpdateMailForPool(Pool $pool, $results)
    {
        foreach ($pool->getEntries() as $entry) {
            $this->sendPoolUpdateMailForEntry($entry, $results);
        }
    }

    /**
     * @param Entry $entry
     * @param $results
     */
    public function sendPoolUpdateMailForEntry(Entry $entry, $results)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-pool_update.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:poolUpdate.html.twig',
                    [
                        'entry' => $entry,
                        'results' => $results,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:poolUpdate.txt.twig',
                    [
                        'entry' => $entry,
                        'results' => $results,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Entry $entry
     */
    public function sendWishlistReminderMail(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-emptyWishlistReminder.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:emptyWishlistReminder.html.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:emptyWishlistReminder.txt.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Entry $entry
     */
    public function sendEntryViewReminderMail(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-viewEntryReminder.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:viewEntryReminder.html.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:viewEntryReminder.txt.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Entry $receiver
     * @param Entry $entry
     */
    public function sendWishlistUpdatedMail(Entry $receiver, Entry $entry)
    {
        $this->translator->setLocale($receiver->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-wishlistChanged.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:wishlistChanged.html.twig',
                    [
                        'entry' => $receiver,
                        'secret_santa' => $entry,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:wishlistChanged.txt.twig',
                    [
                        'entry' => $receiver,
                        'secret_santa' => $entry,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Entry $entry
     */
    public function sendPoolStatusMail(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-pool_status.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:poolStatus.html.twig',
                    [
                        'pool' => $entry->getPool(),
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:poolStatus.txt.twig',
                    [
                        'pool' => $entry->getPool(),
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Pool $pool
     */
    public function sendPoolUpdatedMailsForPool(Pool $pool)
    {
        foreach ($pool->getEntries() as $entry) {
            $this->sendPoolUpdatedMailForEntry($entry);
        }
    }

    /**
     * @param Entry $entry
     */
    public function sendPoolUpdatedMailForEntry(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-updated_party.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:updatedParty.html.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:updatedParty.txt.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/plain'
            )
        );
    }

    public function sendRemovedSecretSantaMail(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-removed_secret_santa.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:removedSecretSanta.html.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:removedSecretSanta.txt.twig',
                    [
                        'entry' => $entry,
                    ]
                ),
                'text/plain'
            )
        );
    }
}
