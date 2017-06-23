<?php

namespace Intracto\SecretSantaBundle\Controller\Party;

use Intracto\SecretSantaBundle\Entity\Party;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendPartyUpdateController extends Controller
{
    /**
     * @Route("/send-party-update/{listurl}", name="send_party_update")
     */
    public function sendPartyUpdateAction(Party $party)
    {
        $results = $this->get('intracto_secret_santa.query.participant_report')->fetchDataForPartyUpdateMail($party->getListurl());

        $this->get('intracto_secret_santa.mailer')->sendPartyUpdateMailForParty($party, $results);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.send_party_update.success')
        );

        return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
    }
}
