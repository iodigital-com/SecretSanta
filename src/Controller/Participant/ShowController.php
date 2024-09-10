<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Entity\Participant;
use App\Form\Type\AnonymousMessageFormType;
use App\Form\Type\WishlistType;
use App\Service\ParticipantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ShowController extends AbstractController
{
    #[Route('/{_locale}/participant/{url}', name: 'participant_view', methods: ['GET'])]
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

        return $this->render('Participant/show/valid.html.twig', [
            'participant' => $participant,
            'wishlistForm' => $wishlistForm->createView(),
            'messageForm' => $messageForm->createView(),
        ]);
    }
}
