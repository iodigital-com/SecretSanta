<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Party;
use App\Form\Type\PartyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/{_locale<en|de|es|fr|hu|it|nl|no|pl|pt>}', name: 'homepage', defaults: ['_locale' => 'en'], methods: ['GET'])]
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
