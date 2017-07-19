<?php

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
     * @Route("/participant/edit/{listurl}/{participantId}", name="participant_edit")
     * @ParamConverter("participant", class="IntractoSecretSantaBundle:Participant", options={"id" = "participantId"})
     * @Method("POST")
     */
    public function editParticipantAction(Request $request, string $listurl, Participant $participant)
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');

        if ($participant->getParty()->getListurl() !== $listurl) {
            $this->createNotFoundException(sprintf('Party with listurl "%s" is not found.', $listurl));
        }

        if (!$this->get('intracto_secret_santa.service.participant')->validateEmail($email)) {
            return new JsonResponse(['success' => false, 'message' => $this->get('translator')->trans('flashes.participant.edit_email')]);
        }

        $orriginalEmail = $participant->getEmail();
        $this->get('intracto_secret_santa.service.participant')->editParticipant($participant, $name, $email);

        $message = $this->get('translator')->trans('flashes.participant.updated_participant');
        if ($orriginalEmail !== $participant->getEmail() && $participant->getParty()->getCreated()) {
            $this->get('intracto_secret_santa.mailer')->sendSecretSantaMailForParticipant($participant);
            $message = $this->get('translator')->trans('flashes.participant.updated_participant_resent');
        }

        return new JsonResponse(['success' => true, 'message' => $message]);
    }

    /**
     * @Route("/participant/remove/{listurl}/{participantId}", name="participant_remove")
     * @ParamConverter("participant", class="IntractoSecretSantaBundle:Participant", options={"id" = "participantId"})
     * @Method("POST")
     */
    public function removeParticipantFromPartyAction(Request $request, $listurl, Participant $participant)
    {
        if (false === $this->isCsrfTokenValid('delete_participant', $request->get('csrf_token'))) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.participant.remove_participant.wrong')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $listurl]));
        }

        $participants = $participant->getParty()->getParticipants();

        if (count($participants) <= 3) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.participant.remove_participant.danger')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $listurl]));
        }

        if ($participant->isPartyAdmin()) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                $this->get('translator')->trans('flashes.participant.remove_participant.warning')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $listurl]));
        }

        $excludeCount = 0;

        foreach ($participants as $user) {
            if (count($user->getExcludedParticipants()) > 0) {
                ++$excludeCount;
            }
        }

        if ($excludeCount > 0 && $participant->getParty()->getCreated()) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                $this->get('translator')->trans('flashes.participant.remove_participant.excluded_participants')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $listurl]));
        }

        if ($excludeCount > 0 && count($participants) == 4) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.participant.remove_participant.not_enough_for_exclude')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $listurl]));
        }

        if ($participant->getParty()->getCreated()) {
            $secretSanta = $participant->getAssignedParticipant();
            $assignedParticipantId = $this->get('intracto_secret_santa.query.participant_report')->findBuddyByParticipantId($participant->getId());
            $assignedParticipant = $this->get('intracto_secret_santa.repository.participant')->find($assignedParticipantId[0]['id']);

            // if A -> B -> A we can't delete B anymore or A is assigned to A
            if ($participant->getAssignedParticipant()->getAssignedParticipant()->getId() === $participant->getId()) {
                $this->get('session')->getFlashBag()->add(
                    'warning',
                    $this->get('translator')->trans('flashes.participant.remove_participant.self_assigned')
                );

                return $this->redirect($this->generateUrl('party_manage', ['listurl' => $listurl]));
            }

            $this->get('doctrine.orm.entity_manager')->remove($participant);
            $this->get('doctrine.orm.entity_manager')->flush();

            $assignedParticipant->setAssignedParticipant($secretSanta);
            $this->get('doctrine.orm.entity_manager')->persist($assignedParticipant);
            $this->get('doctrine.orm.entity_manager')->flush();

            if ($assignedParticipant->isSubscribed()) {
                $this->get('intracto_secret_santa.mailer')->sendRemovedSecretSantaMail($assignedParticipant);
            }
        } else {
            $this->get('doctrine.orm.entity_manager')->remove($participant);
            $this->get('doctrine.orm.entity_manager')->flush();
        }

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.participant.remove_participant.success')
        );

        return $this->redirect($this->generateUrl('party_manage', ['listurl' => $listurl]));
    }
}
