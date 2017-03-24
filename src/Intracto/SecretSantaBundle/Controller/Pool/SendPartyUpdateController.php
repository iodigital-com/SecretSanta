<?php

namespace Intracto\SecretSantaBundle\Controller\Pool;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendPartyUpdateController extends Controller
{
    /**
     * @Route("/send-party-update/{listUrl}", name="send_party_update")
     */
    public function sendPoolUpdateAction($listUrl)
    {
        $results = $this->get('intracto_secret_santa.entry')->fetchDataForPoolUpdateMail($listUrl);
        $party = $this->get('party_repository')->findOneByListurl($listUrl);
        if ($party === null) {
            throw new NotFoundHttpException();
        }

        $this->get('intracto_secret_santa.mail')->sendPoolUpdateMailForPool($party, $results);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.send_party_update.success')
        );

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $party->getListurl()]));
    }
}
