<?php


namespace Intracto\SecretSantaBundle\Controller;


use Intracto\SecretSantaBundle\Response\TransparentPixelResponse;
use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TrackingController extends Controller
{
    /**
     * @Route("email/{participantId}.gif", name="mailopen_tracker")
     */
    public function trackEmailAction($participantId)
    {
        /**@var Participant $participant*/
        $participant = $this->get('intracto_secret_santa.repository.participant')->find($participantId);
        $participant->setOpenEmailDate(new \DateTime());
        $this->get('doctrine.orm.default_entity_manager')->flush($participant);

        return new TransparentPixelResponse();
    }
}
