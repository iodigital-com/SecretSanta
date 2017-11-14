<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Service\UnsubscribeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Component\Translation\TranslatorInterface;

class ResendParticipantController extends AbstractController
{
    private $unsubscribeService;
    private $translator;
    private $mailerService;

    public function __construct(
        UnsubscribeService $unsubscribeService,
        TranslatorInterface $translator,
        MailerService $mailerService
    )
    {
        $this->unsubscribeService = $unsubscribeService;
        $this->translator = $translator;
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/resend/{listurl}/{participantUrl}", name="resend_participant")
     * @ParamConverter("participant", class="IntractoSecretSantaBundle:Participant", options={"mapping": {"participantUrl": "url"}})
     * @Method("GET")
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

            $this->addFlash('success', $this->translator->trans('flashes.resend_participant.resent', ['%email%' => $participant->getName()]));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
    }
}
