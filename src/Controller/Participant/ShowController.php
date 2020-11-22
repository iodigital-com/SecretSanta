<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Service\ParticipantService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\WishlistType;
use App\Form\Type\AnonymousMessageFormType;
use App\Entity\Participant;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    /**
     * @Route("/participant/{url}", name="participant_view", methods={"GET"})
     * @Template("Participant/show/valid.html.twig")
     */
    public function showAction(Request $request, Participant $participant, ParticipantService $participantService)
    {
        if ($participant->getParty()->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('Participant/show/expired.html.twig', [
                'participant' => $participant,
            ]);
        }

        $wishlistForm = $this->createForm(WishlistType::class, $participant, [
            'action' => $this->generateUrl('wishlist_update', ['url' => $participant->getUrl()]),
        ]);
        $messageForm = $this->createForm(AnonymousMessageFormType::class, null, [
            'action' => $this->generateUrl('participant_communication_send_message'),
        ]);

        $participantService->logFirstAccess($participant, $request->getClientIp());

        return [
            'participant' => $participant,
            'wishlistForm' => $wishlistForm->createView(),
            'messageForm' => $messageForm->createView(),
        ];
    }
}
