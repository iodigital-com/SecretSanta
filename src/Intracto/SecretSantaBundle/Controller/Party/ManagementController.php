<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Party;

use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Form\Handler\AddParticipantFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Form\Type\AddParticipantType;
use Intracto\SecretSantaBundle\Form\Type\UpdatePartyDetailsType;
use Intracto\SecretSantaBundle\Form\Type\PartyExcludeParticipantType;

class ManagementController extends Controller
{
    /**
     * @Route("/manage/{listurl}", name="party_manage")
     * @Template("IntractoSecretSantaBundle:Party/manage:valid.html.twig")
     * @Method("GET")
     */
    public function validAction(Party $party, Form $excludeForm = null)
    {
        if ($party->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Party/manage:expired.html.twig', [
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
     * @Route("/manage/update/{listurl}", name="party_manage_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, Party $party)
    {
        $party->setConfirmed(true);
        $updatePartyDetailsForm = $this->createForm(UpdatePartyDetailsType::class, $party, ['validation_groups' => 'Party']);
        $updatePartyDetailsForm->handleRequest($request);

        if ($updatePartyDetailsForm->isSubmitted() && $updatePartyDetailsForm->isValid()) {
            $this->getDoctrine()->getManager()->persist($party);
            $this->getDoctrine()->getManager()->flush();

            if ($party->getCreated()) {
                $this->get('intracto_secret_santa.mailer')->sendPartyUpdatedMailsForParty($party);
            }

            $this->addFlash('success', $this->get('translator')->trans('flashes.management.updated_party.success'));
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('flashes.management.updated_party.danger'));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    /**
     * @Route("/manage/addParticipant/{listurl}", name="party_manage_addParticipant")
     * @Method("POST")
     */
    public function addParticipantAction(Request $request, Party $party, AddParticipantFormHandler $handler)
    {
        $addParticipantForm = $this->createForm(AddParticipantType::class, new Participant());

        $handler->handle($addParticipantForm, $request, $party);

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    /**
     * @Route("/manage/start/{listurl}", name="party_manage_start")
     * @Method("GET")
     */
    public function startPartyAction(Party $party)
    {
        if ($this->get('intracto_secret_santa.service.party')->startParty($party)) {
            $this->addFlash('success', $this->get('translator')->trans('flashes.management.start_party.success'));
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('flashes.management.start_party.danger'));
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }

    /**
     * @Route("/exclude/{listurl}", name="party_exclude")
     * @Method("POST")
     */
    public function excludeAction(Request $request, Party $party)
    {
        if (count($party->getParticipants()) <= 3) {
            $this->addFlash('danger', $this->get('translator')->trans('party_manage_valid.excludes.not_enough'));

            return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
        }

        $form = $this->createForm(PartyExcludeParticipantType::class, $party);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($party);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', $this->get('translator')->trans('flashes.management.excludes.success'));
            } else {
                return $this->forward('IntractoSecretSantaBundle:Party/Management:valid', array('listurl' => $party->getListurl(), 'excludeForm' => $form));
            }
        }

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }
}
