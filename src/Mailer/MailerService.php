<?php

namespace App\Mailer;

use Doctrine\ORM\EntityManagerInterface;
use App\Model\ContactSubmission;
use App\Service\UnsubscribeService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use App\Entity\Participant;
use App\Entity\Party;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class MailerService
{
    public \Swift_Mailer $mailer;
    public \Swift_Mailer $mandrill;
    public EntityManagerInterface $em;
    public Environment $templating;
    public TranslatorInterface $translator;
    public RouterInterface $routing;
    public UnsubscribeService $unsubscribeService;
    public string $noreplyEmail;
    private CheckMailDomainService $checkMailDomainService;
    public string $contactEmail;

    public function __construct(
        \Swift_Mailer $mailer,
        \Swift_Mailer $mandrill,
        EntityManagerInterface $em,
        Environment $templating,
        TranslatorInterface $translator,
        RouterInterface $routing,
        UnsubscribeService $unsubscribeService,
        string $noreplyEmail,
        CheckMailDomainService $checkMailDomainService,
        string $contactEmail
    ) {
        $this->mailer = $mailer;
        $this->mandrill = $mandrill;
        $this->em = $em;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->routing = $routing;
        $this->unsubscribeService = $unsubscribeService;
        $this->noreplyEmail = $noreplyEmail;
        $this->checkMailDomainService = $checkMailDomainService;
        $this->contactEmail = $contactEmail;
    }

    public function sendPendingConfirmationMail(Party $party): void
    {
        $this->translator->setLocale($party->getLocale());

        $message = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-pendingConfirmation.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($party->getOwnerEmail())
            ->setBody(
                $this->templating->render(
                    'Emails/pendingConfirmation.txt.twig',
                    ['party' => $party]
                )
            )
            ->addPart(
                $this->templating->render(
                    'Emails/pendingConfirmation.html.twig',
                    ['party' => $party]
                ),
                'text/html'
            );
        $this->sendMail($message);
    }

    /**
     * Sends out all mails for a Party.
     */
    public function sendSecretSantaMailsForParty(Party $party): void
    {
        $party->setSentdate(new \DateTime('now'));
        $this->em->persist($party);
        $this->em->flush();

        foreach ($party->getParticipants() as $participant) {
            $this->sendSecretSantaMailForParticipant($participant);
        }
    }

    /**
     * Sends out mail for a Participant.
     */
    public function sendSecretSantaMailForParticipant(Participant $participant): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());

        // We wrap the admin's message into our own message and from 19/apr/2017 we no longer save
        // our own message in the DB. Don't wrap older parties here to prevent the message from occuring twice.
        if ($participant->getParty()->getCreationDate() < new \DateTime('2017-04-20')) {
            $message = $participant->getParty()->getMessage();
        } else {
            $dateFormatter = \IntlDateFormatter::create(
                $participant->getParty()->getLocale(),
                \IntlDateFormatter::MEDIUM,
                \IntlDateFormatter::NONE
            );

            $message = $this->translator->trans('party_controller.created.message', [
                '%amount%' => $participant->getParty()->getAmount(),
                '%eventdate%' => $dateFormatter->format($participant->getParty()->getEventdate()->getTimestamp()),
                '%location%' => $participant->getParty()->getLocation(),
                '%message%' => $participant->getParty()->getMessage(),
            ]);
        }

        $participant->setInvitationSentDate(new \DateTime('now'));
        $this->em->persist($participant);
        $this->em->flush();

        $message = str_replace(
            ['(NAME)', '(ADMINISTRATOR)'],
            [$participant->getName(), $participant->getParty()->getOwnerName()],
            $message
        );

        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-participant.subject'))
            ->setFrom($this->noreplyEmail, $participant->getParty()->getOwnerName())
            ->setReplyTo([$participant->getParty()->getOwnerEmail() => $participant->getParty()->getOwnerName()])
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/participant.html.twig',
                    [
                        'message' => $message,
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/participant.txt.twig',
                    [
                        'message' => $message,
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    public function sendForgotLinkMail(string $email): bool
    {
        /** @var Participant[] $participatingIn */
        $participatingIn = $this->em->getRepository(Participant::class)->findAllParticipantsForForgotEmail($email);
        $adminOf = $this->em->getRepository(Party::class)->findAllAdminParties($email);

        if (count($adminOf) === 0 && count($participatingIn) === 0) {
            return false;
        }

        $manageLinks = [];
        foreach ($adminOf as $result) {
            $date = '';
            if ($result['eventdate'] instanceof \DateTime) {
                $date = $result['eventdate']->format('d/m/Y');
            }
            $manageLinks[] = [
                'url' => $this->routing->generate('party_manage', ['listurl' => $result['listurl']], Router::ABSOLUTE_URL),
                'date' => $date,
                'location' => $result['location'],
            ];
        }

        $participantLinks = [];
        foreach ($participatingIn as $participant) {
            $date = '';
            if ($participant->getParty()->getEventdate() instanceof \DateTime) {
                $date = $participant->getParty()->getEventdate()->format('d/m/Y');
            }

            $participantLinks[] = [
                'url' => $this->routing->generate('participant_view', ['url' => $participant->getUrl()], Router::ABSOLUTE_URL),
                'date' => $date,
                'location' => $participant->getParty()->getLocation(),
            ];
        }

        if (count($adminOf)) {
            $this->translator->setLocale($adminOf[0]['locale']);
        } else {
            $this->translator->setLocale($participatingIn[0]->getParty()->getLocale());
        }

        $message = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-forgot_link.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'Emails/forgotLink.html.twig',
                    [
                        'manageLinks' => $manageLinks,
                        'participantLinks' => $participantLinks,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/forgotLink.txt.twig',
                    [
                        'manageLinks' => $manageLinks,
                        'participantLinks' => $participantLinks,
                    ]
                ),
                'text/plain'
            );
        $this->sendMail($message);

        return true;
    }

    public function sendReuseLinksMail(string $email): bool
    {
        $results = $this->em->getRepository(Party::class)->findPartiesToReuse($email);

        if (count($results) === 0) {
            return false;
        }

        $partyLinks = [];
        $text = '';
        foreach ($results as $result) {
            if ($result['eventdate'] instanceof \DateTime) {
                $text = $result['eventdate']->format('d/m/Y').' ';
            }
            $text .= $this->translator->trans('emails-reuse_link.at').' '.$result['location'];
            $partyLinks[] = [
                'url' => $this->routing->generate('party_reuse', ['listurl' => $result['listurl']], Router::ABSOLUTE_URL),
                'text' => $text,
            ];
        }

        $this->translator->setLocale($results[0]['locale']);

        $message = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-reuse_link.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'Emails/reuseLink.html.twig',
                    [
                        'partyLinks' => $partyLinks,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/reuseLink.txt.twig',
                    [
                        'partyLinks' => $partyLinks,
                    ]
                ),
                'text/plain'
            );
        $this->sendMail($message);

        return true;
    }

    public function sendPartyUpdateMailForParty(Party $party, array $results): void
    {
        foreach ($party->getParticipants() as $participant) {
            if ($participant->isSubscribed()) {
                $this->sendPartyUpdateMailForParticipant($participant, $results);
            }
        }
    }

    public function sendPartyUpdateMailForParticipant(Participant $participant, array $results): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-party_update.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/partyUpdate.html.twig',
                    [
                        'participant' => $participant,
                        'results' => $results,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/partyUpdate.txt.twig',
                    [
                        'participant' => $participant,
                        'results' => $results,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    public function sendContactFormEmail(ContactSubmission $contactSubmission): bool
    {
        $this->translator->setLocale('nl');
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-contact.subject').': '.$contactSubmission->getSubject())
            ->setFrom($contactSubmission->getEmail(), $contactSubmission->getName())
            ->setTo($this->contactEmail)
            ->setBody(
                $this->templating->render(
                    'Emails/contact.html.twig',
                    [
                        'submission' => $contactSubmission,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/contact.txt.twig',
                    [
                        'submission' => $contactSubmission,
                    ]
                ),
                'text/plain'
            );
        $this->sendMail($mail);

        return true;
    }

    public function sendWishlistReminderMail(Participant $participant): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-emptyWishlistReminder.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/emptyWishlistReminder.html.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/emptyWishlistReminder.txt.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    public function sendParticipantViewReminderMail(Participant $participant): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-viewParticipantReminder.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/viewParticipantReminder.html.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/viewParticipantReminder.txt.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    public function sendWishlistUpdatedMail(Participant $participant): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-wishlistChanged.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/wishlistChanged.html.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/wishlistChanged.txt.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    public function sendPartyStatusMail(Participant $participant): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-party_status.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/partyStatus.html.twig',
                    [
                        'party' => $participant->getParty(),
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/partyStatus.txt.twig',
                    [
                        'party' => $participant->getParty(),
                        'admin' => $participant,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    public function sendPartyUpdatedMailsForParty(Party $party): void
    {
        foreach ($party->getParticipants() as $participant) {
            if ($participant->isSubscribed()) {
                $this->sendPartyUpdatedMailForParticipant($participant);
            }
        }
    }

    public function sendPartyUpdatedMailForParticipant(Participant $participant): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-updated_party.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/updatedParty.html.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/updatedParty.txt.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    public function sendRemovedSecretSantaMail(Participant $participant): void
    {
        $this->translator->setLocale($participant->getParty()->getLocale());
        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-removed_secret_santa.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($participant->getEmail(), $participant->getName())
            ->setBody(
                $this->templating->render(
                    'Emails/removedSecretSanta.html.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/removedSecretSanta.txt.twig',
                    [
                        'participant' => $participant,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    public function sendAnonymousMessage(Participant $recipient, string $message): void
    {
        $this->translator->setLocale($recipient->getParty()->getLocale());

        $mail = (new \Swift_Message())
            ->setSubject($this->translator->trans('emails-anonymous_message.subject'))
            ->setFrom($this->noreplyEmail, $this->translator->trans('emails-base_email.sender'))
            ->setTo($recipient->getEmail())
            ->setBody(
                $this->templating->render(
                    'Emails/anonymousMessage.html.twig',
                    [
                        'message' => $message,
                        'participant' => $recipient,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/anonymousMessage.txt.twig',
                    [
                        'message' => $message,
                        'participant' => $recipient,
                    ]
                ),
                'text/plain'
            );
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($recipient));
        $this->sendMail($mail);
    }

    private function sendMail(\Swift_Message $mail): void
    {
        // Requesting a delisting from Hotmail does not work. They just accept our email and filter it (not delivered to spam folder).
        // https://support.microsoft.com/en-us/getsupport?oaspworkflow=start_1.0.0.0&wfname=capsub&productkey=edfsmsbl3&ccsid=635688189955348624&wa=wsignin1.0
        // Other people have the same issue https://www.maikel.pro/blog/en-your-own-mailserver-postfixdovecot-but/
        // A solution that works is using Mandrill as a relay for these domains. Hotmail is our 2nd largest recipient (after Gmail) so we can't ignore it
        $mailTo = key($mail->getTo());

        if ($this->checkMailDomainService->isBlacklistedAddress($mailTo)) {
            $this->mandrill->send($mail);
        } else {
            $this->mailer->send($mail);
        }
    }
}
