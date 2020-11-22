<?php

declare(strict_types=1);

namespace App\Controller\Participant;

use App\Mailer\MailerService;
use App\Query\ParticipantReportQuery;
use App\Repository\ParticipantRepository;
use App\Service\ParticipantService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Participant;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ParticipantController extends AbstractController
{
    private TranslatorInterface $translator;

    /**
     * ParticipantController constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/participant/edit/{listurl}/{participantUrl}", name="participant_edit", methods={"POST"})
     * @ParamConverter("participant", class="App\Entity\Participant", options={"mapping": {"participantUrl": "url"}})
     */
    public function editParticipantAction(Request $request, string $listurl, Participant $participant, ParticipantService $participantService, MailerService $mailerService)
    {
        $name = htmlspecialchars($request->request->get('name'), ENT_QUOTES);
        $email = htmlspecialchars($request->request->get('email'), ENT_QUOTES);

        if ($participant->getParty()->getListurl() !== $listurl) {
            throw $this->createNotFoundException(sprintf('Party with listurl "%s" is not found.', $listurl));
        }

        if ($participant->isHashed()) {
            throw $this->createNotFoundException('Participant not found');
        }

        if (!$participantService->validateEmail($email)) {
            return new JsonResponse(['success' => false, 'message' => $this->translator->trans('flashes.participant.edit_email'), 'name' => $name, 'email' => $email]);
        }

        $originalEmail = $participant->getEmail();
        $participantService->editParticipant($participant, $name, $email);

        $message = $this->translator->trans('flashes.participant.updated_participant');
        if ($originalEmail !== $participant->getEmail() && $participant->getParty()->getCreated()) {
            $mailerService->sendSecretSantaMailForParticipant($participant);
            $message = $this->translator->trans('flashes.participant.updated_participant_resent');
        }

        return new JsonResponse(['success' => true, 'message' => html_entity_decode($message), 'name' => $name, 'email' => $email]);
    }

    /**
     * @Route("/participant/remove/{listurl}/{participantUrl}", name="participant_remove", methods={"POST"})
     * @ParamConverter("participant", class="App\Entity\Participant", options={"mapping": {"participantUrl": "url"}})
     */
    public function removeParticipantFromPartyAction(Request $request, $listurl, Participant $participant, ParticipantRepository $participantRepository, ParticipantReportQuery $participantReportQuery, MailerService $mailerService)
    {
        if (false === $this->isCsrfTokenValid('delete_participant', $request->get('csrf_token'))) {
            $this->addFlash('danger', $this->translator->trans('flashes.participant.remove_participant.wrong'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        if ($participant->getParty()->getListurl() !== $listurl) {
            throw $this->createNotFoundException(sprintf('Party with listurl "%s" is not found.', $listurl));
        }

        $participants = $participant->getParty()->getParticipants();

        if (count($participants) <= 3) {
            $this->addFlash('danger', $this->translator->trans('flashes.participant.remove_participant.danger'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        if ($participant->isPartyAdmin()) {
            $this->addFlash('warning', $this->translator->trans('flashes.participant.remove_participant.warning'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        $excludeCount = 0;

        foreach ($participants as $user) {
            if (count($user->getExcludedParticipants()) > 0) {
                ++$excludeCount;
            }
        }

        if ($excludeCount > 0 && $participant->getParty()->getCreated()) {
            $this->addFlash('warning', $this->translator->trans('flashes.participant.remove_participant.excluded_participants'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        if ($excludeCount > 0 && count($participants) == 4) {
            $this->addFlash('danger', $this->translator->trans('flashes.participant.remove_participant.not_enough_for_exclude'));

            return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
        }

        $em = $this->getDoctrine()->getManager();

        if ($participant->getParty()->getCreated()) {
            $secretSanta = $participant->getAssignedParticipant();
            $assignedParticipantId = $participantReportQuery->findBuddyByParticipantId($participant->getId());
            $assignedParticipant = $participantRepository->find($assignedParticipantId[0]['id']);

            // if A -> B -> A we can't delete B anymore or A is assigned to A
            if ($participant->getAssignedParticipant()->getAssignedParticipant()->getId() === $participant->getId()) {
                $this->addFlash('warning', $this->translator->trans('flashes.participant.remove_participant.self_assigned'));

                return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
            }

            $em->remove($participant);
            $em->flush();

            $assignedParticipant->setAssignedParticipant($secretSanta);
            $em->persist($assignedParticipant);
            $em->flush();

            if ($assignedParticipant->isSubscribed()) {
                $mailerService->sendRemovedSecretSantaMail($assignedParticipant);
            }
        } else {
            $em->remove($participant);
            $em->flush();
        }

        $this->addFlash('success', $this->translator->trans('flashes.participant.remove_participant.success'));

        return $this->redirectToRoute('party_manage', ['listurl' => $listurl]);
    }
}
