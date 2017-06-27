<?php

namespace Intracto\SecretSantaBundle\Controller\Party;

use Intracto\SecretSantaBundle\Entity\Party;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Form\Type\AddParticipantType;
use Intracto\SecretSantaBundle\Form\Type\UpdatePartyDetailsType;
use Intracto\SecretSantaBundle\Form\Type\PartyExcludeParticipantType;

class ManagementController extends Controller
{
    /**
     * @Route("/manage/{listurl}", name="party_manage")
     * @Template("IntractoSecretSantaBundle:Party/manage:valid.html.twig")
     */
    public function validAction(Party $party, Form $excludeForm = null)
    {
        $addParticipantForm = $this->createForm(
            AddParticipantType::class,
            new Participant(),
            [
                'action' => $this->generateUrl(
                    'party_manage_addParticipant',
                    ['listurl' => $party->getListurl()]
                ),
            ]
        );
        $updatePartyDetailsForm = $this->createForm(
            UpdatePartyDetailsType::class,
            $party,
            [
                'action' => $this->generateUrl(
                    'party_manage_update',
                    ['listurl' => $party->getListurl()]
                ),
            ]
        );

        // We wrap the admin's message into our own message and from 19/apr/2017 we no longer save
        // our own message in the DB. We don't support older parties to prevent the message from occuring twice.
        if ($party->getCreated() || $party->getCreationDate() < new \DateTime('2017-04-20')) {
            $updatePartyDetailsForm->remove('message');
        }

        if ($party->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Party/manage:expired.html.twig', [
                'party' => $party,
                'delete_party_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_party'),
            ]);
        }
        if ($excludeForm === null) {
            $excludeForm = $this->createForm(PartyExcludeParticipantType::class, $party,
                [
                    'action' => $this->generateUrl('party_exclude', ['listurl' => $party->getListurl()]),
                ]
            );
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
        $updatePartyDetailsForm = $this->createForm(UpdatePartyDetailsType::class, $party);
        $updatePartyDetailsForm->handleRequest($request);

        if ($updatePartyDetailsForm->isSubmitted() && $updatePartyDetailsForm->isValid()) {
            $this->get('doctrine.orm.entity_manager')->persist($party);
            $this->get('doctrine.orm.entity_manager')->flush();

            if ($party->getCreated()) {
                $this->get('intracto_secret_santa.mailer')->sendPartyUpdatedMailsForParty($party);
            }

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('flashes.management.updated_party.success')
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.management.updated_party.danger')
            );
        }

        return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
    }

    /**
     * @Route("/manage/addParticipant/{listurl}", name="party_manage_addParticipant")
     * @Method("POST")
     */
    public function addParticipantAction(Request $request, Party $party)
    {
        $addParticipantForm = $this->createForm(AddParticipantType::class, new Participant());

        $handler = $this->get('intract_secret_santa.form_handler.add_participant');

        $handler->handle($addParticipantForm, $request, $party);

        return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
    }

    /**
     * @Route("/manage/start/{listurl}", name="party_manage_start")
     * @Method("GET")
     */
    public function startPartyAction(Party $party)
    {
        if ($party->getCreated() || $party->getParticipants()->count() < 3) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.management.start_party.danger')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
        }

        $mailerService = $this->get('intracto_secret_santa.mailer');

        $party->setCreated(true);
        $this->get('doctrine.orm.entity_manager')->persist($party);

        $this->get('intracto_secret_santa.service.participant')->shuffleParticipants($party);

        $this->get('doctrine.orm.entity_manager')->flush();

        $mailerService->sendSecretSantaMailsForParty($party);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.management.start_party.success')
        );

        return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
    }

    /**
     * @Route("/exclude/{listurl}", name="party_exclude")
     */
    public function excludeAction(Request $request, Party $party)
    {
        if (count($party->getParticipants()) <= 3) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('party_manage_valid.excludes.not_enough')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
        }

        $form = $this->createForm(PartyExcludeParticipantType::class, $party);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($party);
                $this->get('doctrine.orm.entity_manager')->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('flashes.management.excludes.success')
                );
            } else {
                return $this->forward('IntractoSecretSantaBundle:Party/Management:valid', array('listurl' => $party->getListurl(), 'excludeForm' => $form));
            }
        }

        return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
    }
}
