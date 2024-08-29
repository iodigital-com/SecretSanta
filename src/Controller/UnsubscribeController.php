<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Form\Handler\UnsubscribeFormHandler;
use App\Form\Type\UnsubscribeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UnsubscribeController extends AbstractController
{
	#[Route("/{_locale}/unsubscribe/{url}", name: "unsubscribe_confirm", methods: ["GET", "POST"])]
    public function confirmAction(Request $request, Participant $participant, UnsubscribeFormHandler $handler): RedirectResponse|Response
	{
        $form = $this->createForm(UnsubscribeType::class);

        if ($handler->handle($form, $request, $participant)) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('Participant/unsubscribe.html.twig', [
			'unsubscribeForm' => $form->createView(),
			'participant' => $participant,
			'party' => $participant->getParty(),
		]);
    }
}
