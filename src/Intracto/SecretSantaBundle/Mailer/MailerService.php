<?php

namespace Intracto\SecretSantaBundle\Mailer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\Party;

class MailerService
{
    /** @var \Swift_Mailer */
    public $mailer;
    /** @var EntityManager */
    public $em;
    /** @var EngineInterface */
    public $templating;
    /** @var TranslatorInterface */
    public $translator;
    /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router */
    public $routing;
    public $noreplyEmail;

    /**
     * @param \Swift_Mailer       $mailer     a regular SMTP mailer, bad monitoring, cheap
     * @param EntityManager       $em
     * @param EngineInterface     $templating
     * @param TranslatorInterface $translator
     * @param Router              $routing
     * @param $noreplyEmail
     */
    public function __construct(
        \Swift_Mailer $mailer,
        EntityManager $em,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Router $routing,
        $noreplyEmail
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->routing = $routing;
        $this->noreplyEmail = $noreplyEmail;
    }

    /**
     * @param Party $pool
     */
    public function sendPendingConfirmationMail(Party $pool)
    {
        $this->translator->setLocale($pool->getLocale());

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-pendingConfirmation.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($pool->getOwnerEmail())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pendingConfirmation.txt.twig',
                    ['pool' => $pool]
                )
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:pendingConfirmation.html.twig',
                    ['pool' => $pool]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * Sends out all mails for a Pool.
     *
     * @param Party $pool
     */
    public function sendSecretSantaMailsForPool(Party $pool)
    {
        $pool->setSentdate(new \DateTime('now'));
        $this->em->flush($pool);

        foreach ($pool->getParticipants() as $entry) {
            $this->sendSecretSantaMailForEntry($entry);
        }
    }

    /**
     * Sends out mail for a Entry.
     *
     * @param Participant $entry
     */
    public function sendSecretSantaMailForEntry(Participant $entry)
    {
        $this->translator->setLocale($entry->getParty()->getLocale());

        $message = $entry->getParty()->getMessage();
        $message = str_replace('(NAME)', $entry->getName(), $message);
        $message = str_replace('(ADMINISTRATOR)', $entry->getParty()->getOwnerName(), $message);

        $mail = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-participant.subject'))
            ->setFrom($this->noreplyEmail, $entry->getParty()->getOwnerName())
            ->setReplyTo([$entry->getParty()->getOwnerEmail() => $entry->getParty()->getOwnerName()])
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
        $this->mailer->send($mail);
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function sendForgotManageLinkMail($email)
    {
        $results = $this->em->getRepository('IntractoSecretSantaBundle:Pool')->findAllAdminParties($email);

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
                'url' => $this->routing->generate('party_manage', ['listUrl' => $result['listurl']], Router::ABSOLUTE_URL),
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
                    'IntractoSecretSantaBundle:Emails:forgotLink.txt.twig',
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
     * @param Party $pool
     * @param $results
     */
    public function sendPoolUpdateMailForPool(Party $pool, $results)
    {
        foreach ($pool->getParticipants() as $entry) {
            $this->sendPoolUpdateMailForEntry($entry, $results);
        }
    }

    /**
     * @param Participant $entry
     * @param $results
     */
    public function sendPoolUpdateMailForEntry(Participant $entry, $results)
    {
        $this->translator->setLocale($entry->getParty()->getLocale());
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
     * @param Participant $participant
     */
    public function sendWishlistReminderMail(Participant $participant)
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-emptyWishlistReminder.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:emptyWishlistReminder.html.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:emptyWishlistReminder.txt.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Participant $participant
     */
    public function sendEntryViewReminderMail(Participant $participant)
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-viewEntryReminder.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:viewEntryReminder.html.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:viewEntryReminder.txt.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Participant $receiver
     * @param Participant $participant
     */
    public function sendWishlistUpdatedMail(Participant $receiver, Participant $participant)
    {
        $this->translator->setLocale($receiver->getParty()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-wishlistChanged.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:wishlistChanged.html.twig',
                    [
                        'participant' => $receiver,
                        'secret_santa' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:wishlistChanged.txt.twig',
                    [
                        'participant' => $receiver,
                        'secret_santa' => $participant,
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Participant $participant
     */
    public function sendPartyStatusMail(Participant $participant)
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-pool_status.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:poolStatus.html.twig',
                    [
                        'party' => $participant->getParty(),
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:poolStatus.txt.twig',
                    [
                        'party' => $participant->getParty(),
                    ]
                ),
                'text/plain'
            )
        );
    }

    /**
     * @param Party $pool
     */
    public function sendPoolUpdatedMailsForPool(Party $pool)
    {
        foreach ($pool->getParticipants() as $entry) {
            $this->sendPoolUpdatedMailForEntry($entry);
        }
    }

    /**
     * @param Participant $entry
     */
    public function sendPoolUpdatedMailForEntry(Participant $entry)
    {
        $this->translator->setLocale($entry->getParty()->getLocale());
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

    public function sendRemovedSecretSantaMail(Participant $entry)
    {
        $this->translator->setLocale($entry->getParty()->getLocale());
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

    /**
     * @param $recipient
     * @param $message
     */
    public function sendAnonymousMessage(Participant $recipient, $message)
    {
        $this->translator->setLocale($recipient->getParty()->getLocale());

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails-anonymous_message.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($recipient->getEmail())
            ->setBody(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:anonymousMessage.html.twig',
                    [
                        'name' => $recipient->getName(),
                        'message' => $message,
                        'entry' => $recipient,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'IntractoSecretSantaBundle:Emails:anonymousMessage.txt.twig',
                    [
                        'name' => $recipient->getName(),
                        'message' => $message,
                        'entry' => $recipient,
                    ]
                ),
                'text/plain'
            );
        $this->mailer->send($message);
    }
}
