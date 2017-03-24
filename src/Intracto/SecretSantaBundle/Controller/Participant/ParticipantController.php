<?php

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\EmailAddress;

class ParticipantController extends Controller
{
    /**
     * @Route("/participant/edit-email/{listUrl}/{participantId}", name="participant_email_edit")
     */
    public function editEmailAction(Request $request, $listUrl, $participantId)
    {
        /** @var Participant $participant */
        $participant = $this->get('participant_repository')->find($participantId);

        if ($participant->getParty()->getListurl() === $listUrl) {
            $emailAddress = new EmailAddress($request->request->get('email'));
            $emailAddressErrors = $this->get('validator')->validate($emailAddress);

            if (count($emailAddressErrors) > 0) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $this->get('translator')->trans('flashes.entry.edit_email')
                );
            } else {
                $participant->setEmail((string) $emailAddress);
                $this->get('doctrine.orm.entity_manager')->flush($participant);

                $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForEntry($participant);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('flashes.entry.saved_email')
                );
            }
        }

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
    }

    /**
     * @Route("/participant/remove/{listUrl}/{participantId}", name="participant_remove")
     */
    public function removeEntryFromPoolAction(Request $request, $listUrl, $participantId)
    {
        $correctCsrfToken = $this->isCsrfTokenValid(
            'delete_participant',
            $request->get('csrf_token')
        );

        if ($correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.entry.remove_participant.wrong')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
        }

        /** @var Participant $participant */
        $participant = $this->get('participant_repository')->find($participantId);
        $participants = $participant->getParty()->getParticipants();

        if (count($participants) <= 3) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.entry.remove_participant.danger')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
        }

        if ($participant->isPartyAdmin()) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                $this->get('translator')->trans('flashes.entry.remove_participant.warning')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
        }

        $excludeCount = 0;

        foreach ($participants as $participant) {
            if (count($participant->getExcludedParticipants()) > 0) {
                ++$excludeCount;
            }
        }

        if ($excludeCount > 0) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                $this->get('translator')->trans('flashes.entry.remove_participant.excluded_entries')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
        }

        $secretSanta = $participant->getAssignedParticipant();
        $assignedParticipantId = $this->get('intracto_secret_santa.entry')->findBuddyByEntryId($participantId);
        $assignedParticipant = $this->get('participant_repository')->find($assignedParticipantId[0]['id']);

        // if A -> B -> A we can't delete B anymore or A is assigned to A
        if ($participant->getAssignedParticipant()->getAssignedParticipant()->getId() === $participant->getId()) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                $this->get('translator')->trans('flashes.entry.remove_participant.self_assigned')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
        }

        $this->get('doctrine.orm.entity_manager')->remove($participant);
        $this->get('doctrine.orm.entity_manager')->flush();

        $assignedParticipant->setEntry($secretSanta);
        $this->get('doctrine.orm.entity_manager')->persist($assignedParticipant);
        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('intracto_secret_santa.mail')->sendRemovedSecretSantaMail($assignedParticipant);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.entry.remove_participant.success')
        );

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
    }
}
