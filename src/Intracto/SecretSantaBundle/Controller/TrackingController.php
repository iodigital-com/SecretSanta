<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Entity\Participant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TrackingController extends Controller
{
    /**
     * @Route("email/{participantUrl}.gif", name="mailopen_tracker")
     * @Method("GET")
     */
    public function trackEmailAction($participantUrl)
    {
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->addListener(KernelEvents::TERMINATE,
            function (KernelEvent $event) use ($participantUrl) {
                /** @var Participant $participant */
                $participant = $this->get('intracto_secret_santa.repository.participant')->findOneByUrl($participantUrl);
                if ($participant != null) {
                    $participant->setOpenEmailDate(new \DateTime());
                    $this->get('doctrine.orm.default_entity_manager')->flush($participant);
                }
            }
        );

        $response = Response::create();
        $response->setContent(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='));
        $response->headers->set('Content-Type', 'image/gif');
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setPrivate();
        return $response;
    }
}
