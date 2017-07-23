<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Intracto\SecretSantaBundle\Service\ParticipantService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\WishlistType;
use Intracto\SecretSantaBundle\Form\Type\AnonymousMessageFormType;
use Intracto\SecretSantaBundle\Entity\Participant;

class ShowController extends AbstractController
{
    /**
     * @Route("/participant/{url}", name="participant_view")
     * @Template("IntractoSecretSantaBundle:Participant/show:valid.html.twig")
     * @Method("GET")
     */
    public function showAction(Request $request, Participant $participant, ParticipantService $participantService)
    {
        if ($participant->getParty()->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Participant/show:expired.html.twig', [
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
