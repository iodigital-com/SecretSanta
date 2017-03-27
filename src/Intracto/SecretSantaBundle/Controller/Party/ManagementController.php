<?php

namespace Intracto\SecretSantaBundle\Controller\Party;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Form\Type\AddParticipantType;
use Intracto\SecretSantaBundle\Form\Type\UpdatePartyDetailsType;

class ManagementController extends Controller
{
    /**
     * @Route("/manage/{listUrl}", name="party_manage")
     * @Template("IntractoSecretSantaBundle:Party/manage:valid.html.twig")
     */
    public function validAction(Request $request, $listUrl)
    {
        /** @var \Intracto\SecretSantaBundle\Entity\PartyRepository $party */
        $party = $this->get('party_repository')->findOneByListurl($listUrl);
        if ($party === null) {
            throw new NotFoundHttpException();
        }

        if (!$party->getCreated()) {
            return $this->redirect($this->generateUrl('party_exclude', ['listUrl' => $party->getListurl()]));
        }

        if ($party->getSentdate() === null) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('flashes.management.email_validated')
            );

            $this->get('intracto_secret_santa.mail')->sendSecretSantaMailsForParty($party);
        }

        $addParticipantForm = $this->createForm(
            AddParticipantType::class,
            new Participant(),
            [
                'action' => $this->generateUrl(
                    'party_manage_addParticipant',
                    ['listUrl' => $listUrl]
                ),
            ]
        );
        $updatePartyDetailsForm = $this->createForm(
            UpdatePartyDetailsType::class,
            $party,
            [
                'action' => $this->generateUrl(
                    'party_manage_update',
                    ['listUrl' => $listUrl]
                ),
            ]
        );

        if ($party->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Party/manage:expired.html.twig', [
                'party' => $party,
                'delete_party_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_pool'),
            ]);
        }

        return [
            'addParticipantForm' => $addParticipantForm->createView(),
            'updatePartyDetailsForm' => $updatePartyDetailsForm->createView(),
            'party' => $party,
            'delete_party_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_pool'),
            'delete_participant_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_participant'),
        ];
    }

    /**
     * @Route("/manage/update/{listUrl}", name="party_manage_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, $listUrl)
    {
        /** @var \Intracto\SecretSantaBundle\Entity\PartyRepository $party */
        $party = $this->get('party_repository')->findOneByListurl($listUrl);

        if ($party === null) {
            throw new NotFoundHttpException();
        }

        $updatePartyDetailsForm = $this->createForm(UpdatePartyDetailsType::class, $party);
        $updatePartyDetailsForm->handleRequest($request);

        if ($updatePartyDetailsForm->isSubmitted() && $updatePartyDetailsForm->isValid()) {
            $this->get('doctrine.orm.entity_manager')->persist($party);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->get('intracto_secret_santa.mail')->sendPoolUpdatedMailsForPool($party);

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

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
    }

    /**
     * @Route("/manage/addParticipant/{listUrl}", name="party_manage_addParticipant")
     * @Method("POST")
     */
    public function addParticipantAction(Request $request, $listUrl)
    {
        /** @var \Intracto\SecretSantaBundle\Entity\PartyRepository $party */
        $party = $this->get('party_repository')->findOneByListurl($listUrl);

        if ($party === null) {
            throw new NotFoundHttpException();
        }

        $newParticipant = new Participant();
        $addParticipantForm = $this->createForm(AddParticipantType::class, $newParticipant);
        $addParticipantForm->handleRequest($request);

        if ($addParticipantForm->isSubmitted() && $addParticipantForm->isValid()) {
            $newParticipant->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
            $newParticipant->setParty($party);

            $this->get('doctrine.orm.entity_manager')->persist($newParticipant);
            $this->get('doctrine.orm.entity_manager')->flush($newParticipant);

            $adminId = $this->get('intracto_secret_santa.entry')->findAdminIdByPoolId($party->getId());
            /** @var Participant $admin */
            $admin = $this->get('participant_repository')->findOneById($adminId[0]['id']);
            $adminMatch = $admin->getAssignedParticipant();

            $admin->setAssignedParticipant($newParticipant);
            $this->get('doctrine.orm.entity_manager')->persist($admin);
            $this->get('doctrine.orm.entity_manager')->flush($admin);

            $newParticipant->setAssignedParticipant($adminMatch);
            $this->get('doctrine.orm.entity_manager')->persist($newParticipant);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForParticipant($admin);
            $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForParticipant($newParticipant);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('flashes.management.add_participant.success')
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.management.add_participant.danger')
            );
        }

        return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
    }
}
