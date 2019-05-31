<?php

declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Component\HttpFoundation\JsonResponse;

class ParticipantController extends Controller
{
    /**
     * @Route("/participant/edit/{listurl}/{participantUrl}", name="participant_edit")
     * @ParamConverter("participant", class="IntractoSecretSantaBundle:Participant", options={"mapping": {"participantUrl": "url"}})
     * @Method("POST")
     */
    public function editParticipantAction(Request $request, string $listurl, Participant $participant)
    {
        $name = htmlspecialchars($request->request->get('name'), ENT_QUOTES);
        $email = htmlspecialchars($request->request->get('email'), ENT_QUOTES);

        if ($participant->getParty()->getListurl() !== $listurl) {
            throw $this->createNotFoundException(sprintf('Party with listurl "%s" is not found.', $listurl));
        }

        if (!$this->get('intracto_secret_santa.service.participant')->validateEmail($email)) {
            return new JsonResponse(['success' => false, 'message' => $this->get('translator')->trans('flashes.participant.edit_email')]);
        }

        $originalEmail = $participant->getEmail();
        $this->get('intracto_secret_santa.service.participant')->editParticipant($participant, $name, $email);

        $message = $this->get('translator')->trans('flashes.participant.updated_participant');
        if ($originalEmail !== $participant->getEmail() && $participant->getParty()->getCreated()) {
            $this->get('intracto_secret_santa.mailer')->sendSecretSantaMailForParticipant($participant);
            $message = $this->get('translator')->trans('flashes.participant.updated_participant_resent');
        }

        return new JsonResponse(['success' => true, 'message' => html_entity_decode($message), 'name' => $name, 'email' => $email]);
    }

    /**
     * @Route("/participant/remove/{listurl}/{participantUrl}", name="participant_remove")
     * @ParamConverter("participant", class="IntractoSecretSantaBundle:Participant", options={"mapping": {"participantUrl": "url"}})
     * @Method("POST")
     */
    public function removeParticipantFromPartyAction(Request $request, $listurl, Participant $participant)
    {
        if (false === $this->isCsrfTokenValid('delete_participant', $request->get('csrf_token'))) {
            $this->addFlash('danger', $this->get('translator')->trans('flashes.participant.remove_participant.wrong'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        if ($participant->getParty()->getListurl() !== $listurl) {
            throw $this->createNotFoundException(sprintf('Party with listurl "%s" is not found.', $listurl));
        }

        $participants = $participant->getParty()->getParticipants();

        if (count($participants) <= 3) {
            $this->addFlash('danger', $this->get('translator')->trans('flashes.participant.remove_participant.danger'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        if ($participant->isPartyAdmin()) {
            $this->addFlash('warning', $this->get('translator')->trans('flashes.participant.remove_participant.warning'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        $excludeCount = 0;

        foreach ($participants as $user) {
            if (count($user->getExcludedParticipants()) > 0) {
                ++$excludeCount;
            }
        }

        if ($excludeCount > 0 && $participant->getParty()->getCreated()) {
            $this->addFlash('warning', $this->get('translator')->trans('flashes.participant.remove_participant.excluded_participants'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        if ($excludeCount > 0 && count($participants) == 4) {
            $this->addFlash('danger', $this->get('translator')->trans('flashes.participant.remove_participant.not_enough_for_exclude'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        $em = $this->getDoctrine()->getManager();

        if ($participant->getParty()->getCreated()) {
            $secretSanta = $participant->getAssignedParticipant();
            $assignedParticipantId = $this->get('intracto_secret_santa.query.participant_report')->findBuddyByParticipantId($participant->getId());
            $assignedParticipant = $this->get('intracto_secret_santa.repository.participant')->find($assignedParticipantId[0]['id']);

            // if A -> B -> A we can't delete B anymore or A is assigned to A
            if ($participant->getAssignedParticipant()->getAssignedParticipant()->getId() === $participant->getId()) {
                $this->addFlash('warning', $this->get('translator')->trans('flashes.participant.remove_participant.self_assigned'));

                return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
            }

            $em->remove($participant);
            $em->flush();

            $assignedParticipant->setAssignedParticipant($secretSanta);
            $em->persist($assignedParticipant);
            $em->flush();

            if ($assignedParticipant->isSubscribed()) {
                $this->get('intracto_secret_santa.mailer')->sendRemovedSecretSantaMail($assignedParticipant);
            }
        } else {
            $em->remove($participant);
            $em->flush();
        }

        $this->addFlash('success', $this->get('translator')->trans('flashes.participant.remove_participant.success'));

        return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
    }
}
