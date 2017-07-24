<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intracto\SecretSantaBundle\Entity\Participant;

class ResendParticipantController extends Controller
{
    /**
     * @Route("/resend/{listurl}/{participantId}", name="resend_participant")
     * @ParamConverter("participant", class="IntractoSecretSantaBundle:Participant", options={"id" = "participantId"})
     * @Method("GET")
     */
    public function resendAction($listurl, Participant $participant)
    {
        if ($participant->getParty()->getListurl() !== $listurl) {
            $this->createNotFoundException();
        }

        if ($this->get('intracto_secret_santa.service.unsubscribe')->isBlacklisted($participant)) {
            $this->addFlash('danger', $this->get('translator')->trans('flashes.resend_participant.blacklisted'));
        } else {
            $this->get('intracto_secret_santa.mailer')->sendSecretSantaMailForParticipant($participant);

            $this->addFlash('success', $this->get('translator')->trans('flashes.resend_participant.resent', ['%email%' => $participant->getName()]));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
    }
}
