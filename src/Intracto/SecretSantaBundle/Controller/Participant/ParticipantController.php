<?php

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Intracto\SecretSantaBundle\Validator\ParticipantIsNotBlacklisted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\EmailAddress;
use Symfony\Component\HttpFoundation\JsonResponse;

class ParticipantController extends Controller
{
    /**
     * @Route("/participant/edit", name="participant_edit")
     */
    public function editParticipantAction(Request $request)
    {
        $name = $request->request->get('name');
        $emailAddress = new EmailAddress($request->request->get('email'));
        $participantId = $request->request->get('participantId');
        $listUrl = $request->request->get('listUrl');

        /** @var Participant $participant */
        $participant = $this->get('intracto_secret_santa.repository.participant')->find($participantId);

        if ($participant->getParty()->getListurl() === $listUrl) {
            $emailAddressErrors = $this->get('validator')->validate($emailAddress);
            $blacklisted = $this->get('validator')->validate($emailAddress, new ParticipantIsNotBlacklisted());
            if ((count($emailAddressErrors) + count($blacklisted)) > 0 ) {
                $return = [
                    'responseCode' => 400,
                    'message' => [
                        'type' => 'danger',
                        'message' => $this->get('translator')->trans('flashes.entry.edit_email'),
                        ],
                    ];
            } else {
                $orriginalEmail = $participant->getEmail();
                $participant->setEmail((string) $emailAddress);
                $participant->setName((string) $name);
                $this->get('doctrine.orm.entity_manager')->flush($participant);

                if ($orriginalEmail != $participant->getEmail() && $participant->getParty()->getCreated()) {
                    $this->get('intracto_secret_santa.mailer')->sendSecretSantaMailForParticipant($participant);
                    $message = $this->get('translator')->trans('flashes.entry.updated_participant_resent');
                } else {
                    $message = $this->get('translator')->trans('flashes.entry.updated_participant');
                }
                $return = [
                    'responseCode' => 200,
                    'message' => [
                        'type' => 'success',
                        'message' => $message
                    ]
                ];
            }
        }

        return new JsonResponse($return);
    }

    /**
     * @Route("/participant/remove/{listUrl}/{participantId}", name="participant_remove")
     */
    public function removeParticipantFromPartyAction(Request $request, $listUrl, $participantId)
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
        $participant = $this->get('intracto_secret_santa.repository.participant')->find($participantId);
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

        foreach ($participants as $user) {
            if (count($user->getExcludedParticipants()) > 0) {
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

        if ($participant->getParty()->getCreated()) {
            $secretSanta = $participant->getAssignedParticipant();
            $assignedParticipantId = $this->get('intracto_secret_santa.query.participant_report')->findBuddyByParticipantId($participantId);
            $assignedParticipant = $this->get('intracto_secret_santa.repository.participant')->find($assignedParticipantId[0]['id']);

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
            $this->get('translator')->trans('flashes.entry.remove_participant.success')
        );

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
    }
}
