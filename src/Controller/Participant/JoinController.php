<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Entity\Participant;
use App\Entity\Party;
use App\Form\Type\AddParticipantType;
use App\Repository\PartyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class JoinController extends AbstractController
{
	#[Route("/{_locale}/join/{joinurl}", name: "join_party")]
    public function joinAction(Request $request, string $joinurl, PartyRepository $partyRepository, EntityManagerInterface $em): RedirectResponse|Response
	{
        $addParticipantForm = null;
        /** @var ?Party $party */
        $party = $partyRepository->findOneBy(['joinurl' => $joinurl, 'joinmode' => 1, 'created' => 0]);

        if (null !== $party) {
            $addParticipantForm = $this->createForm(AddParticipantType::class, new Participant(), [
                'action' => $this->generateUrl('join_party', ['joinurl' => $party->getJoinurl()]),
            ]);
            $addParticipantForm->handleRequest($request);

            if ($addParticipantForm->isSubmitted() && $addParticipantForm->isValid()) {
                /** @var Participant $newParticipant */
                $newParticipant = $addParticipantForm->getData();
                $newParticipant->setParty($party);
                $em->persist($newParticipant);
                $em->flush();

                return $this->redirectToRoute('join_party_joined', ['joinurl' => $party->getJoinurl()]);
            }
        }

        return $this->render('Participant/show/join.html.twig', [
			'party' => $party,
			'form' => isset($addParticipantForm) ? $addParticipantForm->createView() : null,
		]);
    }

	#[Route("/{_locale}/joined/{joinurl}", name: "join_party_joined")]
    public function joinedAction(string $joinurl, PartyRepository $partyRepository): Response
	{
        /** @var Party $party */
        $party = $partyRepository->findOneBy(['joinurl' => $joinurl, 'joinmode' => 1, 'created' => 0]);

        return $this->render('Participant/show/join.html.twig', [
			'party' => $party,
			'form' => null,
		]);
    }
}
