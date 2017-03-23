<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Entity\Participant;

class ResendEntryController extends Controller
{
    /**
     * @Route("/resend/{listUrl}/{entryId}", name="resend_entry")
     */
    public function resendAction($listUrl, $entryId)
    {
        /** @var Participant $participant */
        $participant = $this->get('participant_repository')->find($entryId);
        if ($participant === null) {
            throw new NotFoundHttpException();
        }

        if ($participant->getParty()->getListUrl() !== $listUrl) {
            throw new NotFoundHttpException();
        }

        $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForEntry($participant);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.resend_entry.resent', ['%email%' => $participant->getName()])
        );

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }
}
