<?php

namespace Intracto\SecretSantaBundle\Controller\Party;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendPartyUpdateController extends Controller
{
    /**
     * @Route("/send-party-update/{listUrl}", name="send_party_update")
     */
    public function sendPartyUpdateAction($listUrl)
    {
        $results = $this->get('intracto_secret_santa.query.participant_report')->fetchDataForPartyUpdateMail($listUrl);
        $party = $this->get('party_repository')->findOneByListurl($listUrl);
        if ($party === null) {
            throw new NotFoundHttpException();
        }

        $this->get('intracto_secret_santa.mail')->sendPartyUpdateMailForParty($party, $results);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.send_party_update.success')
        );

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $party->getListurl()]));
    }
}
