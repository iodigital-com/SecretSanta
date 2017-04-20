<?php


namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Response\TransparentPixelResponse;
use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TrackingController extends Controller
{
    /**
     * @Route("email/{participantId}.gif", name="mailopen_tracker")
     */
    public function trackEmailAction($participantId)
    {
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->addListener(KernelEvents::TERMINATE,
            function (KernelEvent $event) use ($participantId) {
                /**@var Participant $participant*/
                $participant = $this->get('intracto_secret_santa.repository.participant')->find($participantId);
                $participant->setOpenEmailDate(new \DateTime());
                $this->get('doctrine.orm.default_entity_manager')->flush($participant);
            }
        );

        return new TransparentPixelResponse();
    }
}
