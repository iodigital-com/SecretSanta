<?php

declare(strict_types=1);

namespace App\Controller\Party;

use App\Entity\Party;
use App\Form\Handler\AddParticipantFormHandler;
use App\Mailer\MailerService;
use App\Service\PartyService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Participant;
use App\Form\Type\AddParticipantType;
use App\Form\Type\UpdatePartyDetailsType;
use App\Form\Type\PartyExcludeParticipantType;
use Symfony\Component\Routing\Annotation\Route;
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

    /**
     * @Route("/manage/{listurl}", name="party_manage", methods={"GET"})
     * @Template("Party/manage/valid.html.twig")
     */
    public function validAction(Party $party, Form $excludeForm = null)
    {
        if ($party->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('Party/manage:expired.html.twig', [
                'party' => $party,
                'delete_party_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_party'),
            ]);
        }

        $addParticipantForm = $this->createForm(AddParticipantType::class, new Participant(), [
            'action' => $this->generateUrl('party_manage_addParticipant', ['listurl' => $party->getListurl()]),
        ]);
        $updatePartyDetailsForm = $this->createForm(UpdatePartyDetailsType::class, $party, [
            'action' => $this->generateUrl('party_manage_update', ['listurl' => $party->getListurl()]),
        ]);

        if ($excludeForm === null) {
            $excludeForm = $this->createForm(PartyExcludeParticipantType::class, $party, [
                'action' => $this->generateUrl('party_exclude', ['listurl' => $party->getListurl()]),
            ]);
        }

        return [
            'addParticipantForm' => $addParticipantForm->createView(),
            'updatePartyDetailsForm' => $updatePartyDetailsForm->createView(),
            'party' => $party,
            'delete_party_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_party'),
            'delete_participant_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_participant'),
            'excludeForm' => $excludeForm->createView(),
        ];
    }

    /**
     * @Route("/manage/update/{listurl}", name="party_manage_update", methods={"POST"})
     */
    public function updateAction(Request $request, Party $party, MailerService $mailerService)
    {
        $party->setConfirmed(true);
        $updatePartyDetailsForm = $this->createForm(UpdatePartyDetailsType::class, $party, ['validation_groups' => 'Party']);
        $updatePartyDetailsForm->handleRequest($request);

        if ($updatePartyDetailsForm->isSubmitted() && $updatePartyDetailsForm->isValid()) {
            $this->getDoctrine()->getManager()->persist($party);
            $this->getDoctrine()->getManager()->flush();

            if ($party->getCreated()) {
                $mailerService->sendPartyUpdatedMailsForParty($party);
            }

            $this->addFlash('success', $this->translator->trans('flashes.management.updated_party.success'));
        } else {
            $this->addFlash('danger', $this->translator->trans('flashes.management.updated_party.danger'));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    /**
     * @Route("/manage/addParticipant/{listurl}", name="party_manage_addParticipant", methods={"POST"})
     */
    public function addParticipantAction(Request $request, Party $party, AddParticipantFormHandler $handler)
    {
        $addParticipantForm = $this->createForm(AddParticipantType::class, new Participant());

        $handler->handle($addParticipantForm, $request, $party);

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    /**
     * @Route("/manage/start/{listurl}", name="party_manage_start", methods={"GET"})
     */
    public function startPartyAction(Party $party, PartyService $partyService)
    {
        if ($partyService->startParty($party)) {
            $this->addFlash('success', $this->translator->trans('flashes.management.start_party.success'));
        } else {
            $this->addFlash('danger', $this->translator->trans('flashes.management.start_party.danger'));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    /**
     * @Route("/exclude/{listurl}", name="party_exclude", methods={"POST"})
     */
    public function excludeAction(Request $request, Party $party)
    {
        if (count($party->getParticipants()) <= 3) {
            $this->addFlash('danger', $this->translator->trans('party_manage_valid.excludes.not_enough'));

            return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
        }

        $form = $this->createForm(PartyExcludeParticipantType::class, $party);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($party);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', $this->translator->trans('flashes.management.excludes.success'));
            } else {
                return $this->forward(ManagementController::class.':valid', ['listurl' => $party->getListurl(), 'excludeForm' => $form]);
            }
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }
}
