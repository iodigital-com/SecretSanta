<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Form\Handler\UnsubscribeFormHandler;
use App\Form\Type\UnsubscribeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UnsubscribeController extends AbstractController
{
    /**
     * @Route("/unsubscribe/{url}", name="unsubscribe_confirm", methods={"GET", "POST"})
     * @Template("Participant/unsubscribe.html.twig")
     */
    public function confirmAction(Request $request, Participant $participant, UnsubscribeFormHandler $handler)
    {
        $form = $this->createForm(UnsubscribeType::class);

        if ($handler->handle($form, $request, $participant)) {
            return $this->redirectToRoute('homepage');
        }

        return [
            'unsubscribeForm' => $form->createView(),
            'participant' => $participant,
            'party' => $participant->getParty(),
        ];
    }
}
