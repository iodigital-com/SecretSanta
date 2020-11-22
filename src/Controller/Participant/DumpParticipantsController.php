<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Repository\ParticipantRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DumpParticipantsController extends AbstractController
{
    /**
     * @Route("/dump-participants", name="dump_participants", methods={"GET"})
     * @Template("Participant/dumpParticipants.html.twig")
     */
    public function dumpAction(ParticipantRepository $repository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADWORDS');

        $startCrawling = new \DateTime();
        $startCrawling->sub(new \DateInterval('P4M'));

        return [
            'participants' => $repository->findAfter($startCrawling),
        ];
    }
}
