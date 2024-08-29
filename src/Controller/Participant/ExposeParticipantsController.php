<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Entity\Party;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExposeParticipantsController extends AbstractController
{
	#[Route("/{_locale}/participants/expose/{listurl}", name: "expose_participants", methods: ["GET"])]
    public function indexAction(Party $party): Response
	{
        return $this->render('Participant/exposeAll.html.twig', [
			'party' => $party,
		]);
    }
}
