<?php

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Form\Type\WishlistType;
use Intracto\SecretSantaBundle\Form\Type\AnonymousMessageFormType;
use Intracto\SecretSantaBundle\Entity\Participant;

class ShowController extends Controller
{
    /**
     * @Route("/entry/{url}", name="participant_view")
     * @Template("IntractoSecretSantaBundle:Participant/show:valid.html.twig")
     */
    public function showAction(Request $request, $url)
    {
        /** @var Participant $participant */
        $participant = $this->get('participant_repository')->findOneByUrl($url);
        if ($participant === null) {
            throw new NotFoundHttpException();
        }

        if ($participant->getParty()->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Participant/show:expired.html.twig', [
                'participant' => $participant,
            ]);
        }

        $wishlistForm = $this->createForm(
            WishlistType::class,
            $participant,
            [
                'action' => $this->generateUrl(
                    'wishlist_update',
                    ['url' => $participant->getUrl()]
                ),
            ]
        );
        $messageForm = $this->createForm(
            AnonymousMessageFormType::class,
            null,
            [
                'action' => $this->generateUrl('participant_communication_send_message'),
            ]
        );

        // Log visit on first access
        if ($participant->getViewdate() === null) {
            $participant->setViewdate(new \DateTime());
            $this->get('doctrine.orm.entity_manager')->flush($participant);
        }

        // Log ip address on first access
        if ($participant->getIp() === null) {
            $ip = $request->getClientIp();
            $participant->setIp($ip);
            $this->get('doctrine.orm.entity_manager')->flush($participant);
        }

        if (!$request->isXmlHttpRequest()) {
            return [
                'participant' => $participant,
                'wishlistForm' => $wishlistForm->createView(),
                'messageForm' => $messageForm->createView(),
            ];
        }
    }
}
