<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Entity\Participant;
use App\Entity\Party;
use App\Form\Type\AddParticipantType;
use App\Repository\PartyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class JoinController extends AbstractController
{
    /**
     * @Route("/join/{joinurl}", name="join_party")
     * @Template("Participant/show/join.html.twig")
     */
    public function joinAction(Request $request, string $joinurl, PartyRepository $partyRepository)
    {
        /** @var Party $party */
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
                $this->getDoctrine()->getManager()->persist($newParticipant);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('join_party_joined', ['joinurl' => $party->getJoinurl()]);
            }
        }

        return [
            'party' => $party,
            'form' => isset($addParticipantForm) ? $addParticipantForm->createView() : null,
        ];
    }

    /**
     * @Route("/joined/{joinurl}", name="join_party_joined")
     * @Template("Participant/show/join.html.twig")
     */
    public function joinedAction(string $joinurl, PartyRepository $partyRepository)
    {
        /** @var Party $party */
        $party = $partyRepository->findOneBy(['joinurl' => $joinurl, 'joinmode' => 1, 'created' => 0]);

        return [
            'party' => $party,
            'form' => null,
        ];
    }
}
