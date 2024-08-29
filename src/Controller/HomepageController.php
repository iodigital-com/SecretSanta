<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\Type\PartyType;
use App\Entity\Party;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
	#[Route("/{_locale}", name: "homepage", defaults: ["_locale" => "en"], methods: ["GET"])]
    public function indexAction(): Response
	{
        $partyForm = $this->createForm(PartyType::class, new Party(), [
            'action' => $this->generateUrl('create_party'),
        ]);

        return $this->render('Party/create.html.twig', [
			'form' => $partyForm->createView(),
		]);
    }
}
