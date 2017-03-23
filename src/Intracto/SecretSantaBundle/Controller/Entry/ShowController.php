<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Form\Type\WishlistType;
use Intracto\SecretSantaBundle\Form\Type\AnonymousMessageFormType;

class ShowController extends Controller
{
    /**
     * @Route("/entry/{url}", name="entry_view")
     * @Template("IntractoSecretSantaBundle:Entry/show:valid.html.twig")
     */
    public function showAction(Request $request, $url)
    {
        $entry = $this->get('entry_repository')->findOneByUrl($url);
        if ($entry === null) {
            throw new NotFoundHttpException();
        }

        if ($entry->getPool()->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Entry/show:expired.html.twig', [
                'entry' => $entry,
            ]);
        }

        $wishlistForm = $this->createForm(
            WishlistType::class,
            $entry,
            [
                'action' => $this->generateUrl(
                    'wishlist_update',
                    ['url' => $entry->getUrl()]
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
        if ($entry->getViewdate() === null) {
            $entry->setViewdate(new \DateTime());
            $this->get('doctrine.orm.entity_manager')->flush($entry);
        }

        // Log ip address on first access
        if ($entry->getIp() === null) {
            $ip = $request->getClientIp();
            $entry->setIp($ip);
            $this->get('doctrine.orm.entity_manager')->flush($entry);
        }

        if (!$request->isXmlHttpRequest()) {
            return [
                'entry' => $entry,
                'wishlistForm' => $wishlistForm->createView(),
                'messageForm' => $messageForm->createView(),
            ];
        }
    }
}
