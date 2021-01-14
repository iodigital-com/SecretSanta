<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Mailer\MailerService;
use App\Service\UnsubscribeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Participant;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResendParticipantController extends AbstractController
{
    private $unsubscribeService;

    private $translator;

    private $mailerService;

    public function __construct(
        UnsubscribeService $unsubscribeService,
        TranslatorInterface $translator,
        MailerService $mailerService
    ) {
        $this->unsubscribeService = $unsubscribeService;
        $this->translator = $translator;
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/resend/{listurl}/{participantUrl}", name="resend_participant", methods={"GET"})
     * @ParamConverter("participant", class="App\Entity\Participant", options={"mapping": {"participantUrl": "url"}})
     */
    public function resendAction($listurl, Participant $participant)
    {
        if ($participant->getParty()->getListurl() !== $listurl) {
            throw $this->createNotFoundException();
        }

        if ($this->unsubscribeService->isBlacklisted($participant)) {
            $this->addFlash('danger', $this->translator->trans('flashes.resend_participant.blacklisted'));
        } else {
            $this->mailerService->sendSecretSantaMailForParticipant($participant);

            $name = htmlspecialchars($participant->getName(), ENT_QUOTES);
            $this->addFlash('success', $this->translator->trans('flashes.resend_participant.resent', ['%email%' => $name]));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
    }
}
