<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Party;

use Intracto\SecretSantaBundle\Entity\Party;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SendPartyUpdateController extends Controller
{
    /**
     * @Route("/send-party-update/{listurl}", name="send_party_update")
     * @Method("GET")
     */
    public function sendPartyUpdateAction(Party $party)
    {
        $results = $this->get('intracto_secret_santa.query.participant_report')->fetchDataForPartyUpdateMail($party->getListurl());

        $this->get('intracto_secret_santa.mailer')->sendPartyUpdateMailForParty($party, $results);

        $this->addFlash('success', $this->get('translator')->trans('flashes.send_party_update.success'));

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }
}
