<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DumpParticipantsController extends AbstractController
{
    #[Route('/{_locale}/dump-participants', name: 'dump_participants', methods: ['GET'])]
    public function dumpAction(ParticipantRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADWORDS');

        $startCrawling = new \DateTime();
        $startCrawling->sub(new \DateInterval('P4M'));

        return $this->render('Participant/dumpParticipants.html.twig', [
            'participants' => $repository->findAfter($startCrawling),
        ]);
    }
}
