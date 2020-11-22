<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Entity\Party;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExposeParticipantsController extends AbstractController
{
    /**
     * @Route("/participants/expose/{listurl}", name="expose_participants", methods={"GET"})
     * @Template("Participant/exposeAll.html.twig")
     */
    public function indexAction(Party $party)
    {
        return [
            'party' => $party,
        ];
    }
}
