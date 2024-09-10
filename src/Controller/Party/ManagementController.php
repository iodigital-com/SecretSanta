<?php

declare(strict_types=1);

namespace App\Controller\Party;

use App\Entity\Participant;
use App\Entity\Party;
use App\Form\Handler\AddParticipantFormHandler;
use App\Form\Type\AddParticipantType;
use App\Form\Type\PartyExcludeParticipantType;
use App\Form\Type\SetJoinModeType;
use App\Form\Type\UpdatePartyDetailsType;
use App\Mailer\MailerService;
use App\Query\ParticipantReportQuery;
use App\Service\PartyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ManagementController extends AbstractController
{
    private TranslatorInterface $translator;

    /**
     * ManagementController constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/{_locale}/manage/{listurl}', name: 'party_manage', methods: ['GET'])]
    public function validAction(
        Party $party,
        ParticipantReportQuery $reportQueriesService,
        CsrfTokenManagerInterface $csrfTokenManager,
        ?Form $excludeForm = null): Response
    {
        if ($party->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('Party/manage:expired.html.twig', [
                'party' => $party,
                'delete_party_csrf_token' => $csrfTokenManager->getToken('delete_party'),
            ]);
        }

        $addParticipantForm = $this->createForm(AddParticipantType::class, new Participant(), [
            'action' => $this->generateUrl('party_manage_addParticipant', ['listurl' => $party->getListurl()]),
        ]);
        $updatePartyDetailsForm = $this->createForm(UpdatePartyDetailsType::class, $party, [
            'action' => $this->generateUrl('party_manage_update', ['listurl' => $party->getListurl()]),
        ]);
        $setJoinModeForm = $this->createForm(SetJoinModeType::class, $party, [
            'action' => $this->generateUrl('party_manage_joinmode', ['listurl' => $party->getListurl()]),
        ]);

        if (null === $excludeForm) {
            $excludeForm = $this->createForm(PartyExcludeParticipantType::class, $party, [
                'action' => $this->generateUrl('party_exclude', ['listurl' => $party->getListurl()]),
            ]);
        }

        $partyEmailInfo = $reportQueriesService->fetchDataForPartyUpdateMail($party->getListurl());

        return $this->render('Party/manage/valid.html.twig', [
            'addParticipantForm' => $addParticipantForm->createView(),
            'updatePartyDetailsForm' => $updatePartyDetailsForm->createView(),
            'party' => $party,
            'delete_party_csrf_token' => $csrfTokenManager->getToken('delete_party'),
            'delete_participant_csrf_token' => $csrfTokenManager->getToken('delete_participant'),
            'excludeForm' => $excludeForm->createView(),
            'setJoinModeForm' => $setJoinModeForm->createView(),
            'partyEmailInfo' => $partyEmailInfo,
        ]);
    }

    #[Route('/{_locale}/manage/update/{listurl}', name: 'party_manage_update', methods: ['POST'])]
    public function updateAction(Request $request, Party $party, MailerService $mailerService, EntityManagerInterface $em): RedirectResponse
    {
        $party->setConfirmed(true);
        $updatePartyDetailsForm = $this->createForm(UpdatePartyDetailsType::class, $party, ['validation_groups' => 'Party']);
        $updatePartyDetailsForm->handleRequest($request);

        if ($updatePartyDetailsForm->isSubmitted() && $updatePartyDetailsForm->isValid()) {
            $em->persist($party);
            $em->flush();

            if ($party->getCreated()) {
                $mailerService->sendPartyUpdatedMailsForParty($party);
            }

            $this->addFlash('success', $this->translator->trans('flashes.management.updated_party.success'));
        } else {
            $this->addFlash('danger', $this->translator->trans('flashes.management.updated_party.danger'));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    #[Route('/{_locale}/manage/addParticipant/{listurl}', name: 'party_manage_addParticipant', methods: ['POST'])]
    public function addParticipantAction(Request $request, Party $party, AddParticipantFormHandler $handler): RedirectResponse
    {
        $addParticipantForm = $this->createForm(AddParticipantType::class, new Participant());

        $handler->handle($addParticipantForm, $request, $party);

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    #[Route('/{_locale}/manage/start/{listurl}', name: 'party_manage_start', methods: ['GET'])]
    public function startPartyAction(Party $party, PartyService $partyService): RedirectResponse
    {
        if ($partyService->startParty($party)) {
            $this->addFlash('success', $this->translator->trans('flashes.management.start_party.success'));
        } else {
            $this->addFlash('danger', $this->translator->trans('flashes.management.start_party.danger'));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    #[Route('/{_locale}/exclude/{listurl}', name: 'party_exclude', methods: ['POST'])]
    public function excludeAction(Request $request, Party $party, EntityManagerInterface $em): RedirectResponse|Response
    {
        if (count($party->getParticipants()) <= 3) {
            $this->addFlash('danger', $this->translator->trans('party_manage_valid.excludes.not_enough'));

            return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
        }

        $form = $this->createForm(PartyExcludeParticipantType::class, $party);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($party);
                $em->flush();

                $this->addFlash('success', $this->translator->trans('flashes.management.excludes.success'));
            } else {
                return $this->forward(ManagementController::class.':validAction', ['listurl' => $party->getListurl(), 'excludeForm' => $form]);
            }
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    #[Route('/{_locale}/manage/joinmode/{listurl}', name: 'party_manage_joinmode', methods: ['POST'])]
    public function joinModeAction(Request $request, Party $party, EntityManagerInterface $em): RedirectResponse
    {
        $party->setConfirmed(true);
        $setJoinModeForm = $this->createForm(SetJoinModeType::class, $party, []);
        $setJoinModeForm->handleRequest($request);

        if ($setJoinModeForm->isSubmitted() && $setJoinModeForm->isValid()) {
            if ((1 == $party->getJoinmode() && null === $party->getJoinurl()) || '1' == $request->request->get('reset', 0)) {
                // generate join URL
                $party->setJoinurl(base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36));
            }

            $em->persist($party);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('flashes.management.join_mode.success'));
        } else {
            $this->addFlash('danger', $this->translator->trans('flashes.management.join_mode.danger'));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }
}
