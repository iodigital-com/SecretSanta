<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\Type\PartyType;
use App\Entity\Party;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"GET"})
     * @Template("Party/create.html.twig")
     */
    public function indexAction()
    {
        $partyForm = $this->createForm(PartyType::class, new Party(), [
            'action' => $this->generateUrl('create_party'),
        ]);

        return [
            'form' => $partyForm->createView(),
        ];
    }
}
