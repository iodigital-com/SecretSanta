<?php

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Entity\Participant;

class ResendParticipantController extends Controller
{
    /**
     * @Route("/resend/{listUrl}/{participantId}", name="resend_participant")
     */
    public function resendAction($listUrl, $participantId)
    {
        /** @var Participant $participant */
        $participant = $this->get('participant_repository')->find($participantId);
        if ($participant === null) {
            throw new NotFoundHttpException();
        }

        if ($participant->getParty()->getListUrl() !== $listUrl) {
            throw new NotFoundHttpException();
        }

        $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForParticipant($participant);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.resend_entry.resent', ['%email%' => $participant->getName()])
        );

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
    }
}
