<?php

namespace Intracto\SecretSantaBundle\Controller\Pool;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SendPartyUpdateController extends Controller
{
    /**
     * @Route("/send-party-update/{listUrl}", name="send_party_update")
     */
    public function sendPoolUpdateAction($listUrl)
    {
        $results = $this->get('intracto_secret_santa.entry')->fetchDataForPoolUpdateMail($listUrl);
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if (!is_object($pool)) {
            throw new NotFoundHttpException();
        }

        $this->get('intracto_secret_santa.mail')->sendPoolUpdateMailForPool($pool, $results);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.pool_update.success')
        );

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $pool->getListurl()]));
    }
}
