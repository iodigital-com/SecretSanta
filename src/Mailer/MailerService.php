<?php

namespace App\Mailer;

use App\Entity\Participant;
use App\Entity\Party;
use App\Model\ContactSubmission;
use App\Repository\ParticipantRepository;
use App\Repository\PartyRepository;
use App\Service\UnsubscribeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private RouterInterface $routing,
        private UnsubscribeService $unsubscribeService,
        private string $noreplyEmail,
        private CheckMailDomainService $checkMailDomainService,
        private string $contactEmail,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendPendingConfirmationMail(Party $party): void
    {
        $locale = $party->getLocale();

        $message = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-pendingConfirmation.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to($party->getOwnerEmail())
            ->htmlTemplate('Emails/pendingConfirmation.html.twig')
            ->textTemplate('Emails/pendingConfirmation.txt.twig')
            ->context([
                'party' => $party,
                'locale' => $locale,
            ]);
        $this->sendMail($message);
    }

    /**
     * Sends out all mails for a Party.
     *
     * @throws TransportExceptionInterface
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
     *
     * @throws TransportExceptionInterface
     */
    public function sendSecretSantaMailForParticipant(Participant $participant): void
    {
        $locale = $participant->getParty()->getLocale();

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
            ], 'messages', $locale);
        }

        $participant->setInvitationSentDate(new \DateTime('now'));
        $this->em->persist($participant);
        $this->em->flush();

        $message = str_replace(
            ['(NAME)', '(ADMINISTRATOR)'],
            [$participant->getName(), $participant->getParty()->getOwnerName()],
            $message
        );

        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-participant.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $participant->getParty()->getOwnerName()))
            ->replyTo(new Address($participant->getParty()->getOwnerEmail(), $participant->getParty()->getOwnerName()))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/participant.html.twig')
            ->textTemplate('Emails/participant.txt.twig')
            ->context([
                'message' => $message,
                'participant' => $participant,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendForgotLinkMail(string $email): bool
    {
        /** @var ParticipantRepository $participantRepository */
        $participantRepository = $this->em->getRepository(Participant::class);
        /** @var PartyRepository $partyRepository */
        $partyRepository = $this->em->getRepository(Party::class);
        /** @var Participant[] $participatingIn */
        $participatingIn = $participantRepository->findAllParticipantsForForgotEmail($email);
        $adminOf = $partyRepository->findAllAdminParties($email);

        if (0 === count($adminOf) && 0 === count($participatingIn)) {
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
            $locale = $adminOf[0]['locale'];
        } else {
            $locale = $participatingIn[0]->getParty()->getLocale();
        }

        $message = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-forgot_link.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to($email)
            ->htmlTemplate('Emails/forgotLink.html.twig')
            ->textTemplate('Emails/forgotLink.txt.twig')
            ->context([
                'manageLinks' => $manageLinks,
                'participantLinks' => $participantLinks,
                'locale' => $locale,
            ]);
        $this->sendMail($message);

        return true;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendReuseLinksMail(string $email): bool
    {
        /** @var PartyRepository $partyRepository */
        $partyRepository = $this->em->getRepository(Party::class);
        $results = $partyRepository->findPartiesToReuse($email);

        if (0 === count($results)) {
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

        $locale = $results[0]['locale'];

        $message = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-reuse_link.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to($email)
            ->htmlTemplate('Emails/reuseLink.html.twig')
            ->textTemplate('Emails/reuseLink.txt.twig')
            ->context([
                'partyLinks' => $partyLinks,
                'locale' => $locale,
            ]);
        $this->sendMail($message);

        return true;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendPartyUpdateMailForParty(Party $party, array $results): void
    {
        foreach ($party->getParticipants() as $participant) {
            if ($participant->isSubscribed()) {
                $this->sendPartyUpdateMailForParticipant($participant, $results);
            }
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendPartyUpdateMailForParticipant(Participant $participant, array $results): void
    {
        $locale = $participant->getParty()->getLocale();

        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-party_update.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/partyUpdate.html.twig')
            ->textTemplate('Emails/partyUpdate.txt.twig')
            ->context([
                'participant' => $participant,
                'results' => $results,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendContactFormEmail(ContactSubmission $contactSubmission): bool
    {
        $locale = 'nl';
        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-contact.subject', [], 'messages', $locale).': '.$contactSubmission->getSubject())
            ->from(new Address($contactSubmission->getEmail(), $contactSubmission->getName()))
            ->to($this->contactEmail)
            ->htmlTemplate('Emails/contact.html.twig')
            ->textTemplate('Emails/contact.txt.twig')
            ->context([
                'submission' => $contactSubmission,
                'locale' => $locale,
            ]);
        $this->sendMail($mail);

        return true;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendWishlistReminderMail(Participant $participant): void
    {
        $locale = $participant->getParty()->getLocale();
        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-emptyWishlistReminder.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/emptyWishlistReminder.html.twig')
            ->textTemplate('Emails/emptyWishlistReminder.txt.twig')
            ->context([
                'participant' => $participant,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendParticipantViewReminderMail(Participant $participant): void
    {
        $locale = $participant->getParty()->getLocale();
        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-viewParticipantReminder.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/viewParticipantReminder.html.twig')
            ->textTemplate('Emails/viewParticipantReminder.txt.twig')
            ->context([
                'participant' => $participant,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendWishlistUpdatedMail(Participant $participant): void
    {
        $locale = $participant->getParty()->getLocale();
        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-wishlistChanged.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/wishlistChanged.html.twig')
            ->textTemplate('Emails/wishlistChanged.txt.twig')
            ->context([
                'participant' => $participant,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendPartyStatusMail(Participant $participant): void
    {
        $locale = $participant->getParty()->getLocale();
        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-party_status.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/partyStatus.html.twig')
            ->textTemplate('Emails/partyStatus.txt.twig')
            ->context([
                'party' => $participant->getParty(),
                'admin' => $participant,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendPartyUpdatedMailsForParty(Party $party): void
    {
        foreach ($party->getParticipants() as $participant) {
            if ($participant->isSubscribed()) {
                $this->sendPartyUpdatedMailForParticipant($participant);
            }
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendPartyUpdatedMailForParticipant(Participant $participant): void
    {
        $locale = $participant->getParty()->getLocale();
        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-updated_party.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/updatedParty.html.twig')
            ->textTemplate('Emails/updatedParty.txt.twig')
            ->context([
                'participant' => $participant,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendRemovedSecretSantaMail(Participant $participant): void
    {
        $locale = $participant->getParty()->getLocale();
        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-removed_secret_santa.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to(new Address($participant->getEmail(), $participant->getName()))
            ->htmlTemplate('Emails/removedSecretSanta.html.twig')
            ->textTemplate('Emails/removedSecretSanta.txt.twig')
            ->context([
                'participant' => $participant,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($participant));
        $this->sendMail($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendAnonymousMessage(Participant $recipient, string $message): void
    {
        $locale = $recipient->getParty()->getLocale();

        $mail = (new TemplatedEmail())
            ->subject($this->translator->trans('emails-anonymous_message.subject', [], 'messages', $locale))
            ->from(new Address($this->noreplyEmail, $this->translator->trans('emails-base_email.sender', [], 'messages', $locale)))
            ->to($recipient->getEmail())
            ->htmlTemplate('Emails/anonymousMessage.html.twig')
            ->textTemplate('Emails/anonymousMessage.txt.twig')
            ->context([
                'message' => $message,
                'participant' => $recipient,
                'locale' => $locale,
            ]);
        $mail->getHeaders()->addTextHeader('List-Unsubscribe', $this->unsubscribeService->getUnsubscribeLink($recipient));
        $this->sendMail($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendMail(TemplatedEmail $mail): void
    {
        // Requesting a delisting from Hotmail does not work. They just accept our email and filter it (not delivered to spam folder).
        // https://support.microsoft.com/en-us/getsupport?oaspworkflow=start_1.0.0.0&wfname=capsub&productkey=edfsmsbl3&ccsid=635688189955348624&wa=wsignin1.0
        // Other people have the same issue https://www.maikel.pro/blog/en-your-own-mailserver-postfixdovecot-but/
        // A solution that works is using Mandrill as a relay for these domains. Hotmail is our 2nd largest recipient (after Gmail) so we can't ignore it
        $mailTo = $mail->getTo();

        $firstAddress = $mailTo[0];

        if ($this->checkMailDomainService->isBlacklistedAddress($firstAddress->getAddress())) {
            $mail->getHeaders()->addTextHeader('X-Transport', 'mandrill');
        }

        $this->mailer->send($mail);
    }
}
