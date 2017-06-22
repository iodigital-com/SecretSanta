<?php

namespace Intracto\SecretSantaBundle\Controller\Party;

use Intracto\SecretSantaBundle\Form\Handler\PartyFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Form\Type\PartyType;

class PartyController extends Controller
{
    /**
     * @Route("/party/create", name="create_party")
     * @Method("POST")
     * @Template("IntractoSecretSantaBundle:Party:create.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->handlePartyCreation($request, new Party());
    }

    /**
     * @Route("/created/{listurl}", name="party_created")
     * @Template("IntractoSecretSantaBundle:Party:created.html.twig")
     */
    public function createdAction(Party $party)
    {
        return [
            'party' => $party,
        ];
    }

    /**
     * @Route("/reuse/{listurl}", name="party_reuse")
     * @Template("IntractoSecretSantaBundle:Party:create.html.twig")
     */
    public function reuseAction(Request $request, Party $party)
    {
        $party = $party->createNewPartyForReuse();

        return $this->handlePartyCreation($request, $party);
    }

    /**
     * @Route("/delete/{listurl}", name="party_delete")
     * @Template("IntractoSecretSantaBundle:Party:deleted.html.twig")
     */
    public function deleteAction(Request $request, Party $party)
    {
        $correctCsrfToken = $this->isCsrfTokenValid(
            'delete_party',
            $request->get('csrf_token')
        );
        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($this->get('translator')->trans('party_manage_valid.delete.phrase_to_type')));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('flashes.party.not_deleted')
            );

            return $this->redirect($this->generateUrl('party_manage', ['listurl' => $party->getListurl()]));
        }

        $this->get('doctrine.orm.entity_manager')->remove($party);
        $this->get('doctrine.orm.entity_manager')->flush();
    }

    /**
     * @param Request $request
     * @param Party   $party
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function handlePartyCreation(Request $request, Party $party)
    {
        $form = $this->createForm(PartyType::class, $party, [
            'action' => $this->generateUrl('create_party'),
        ]);

        /** @var PartyFormHandler $handler */
        $handler = $this->get('intracto_secret_santa.form_handler.party');

        if ($handler->handle($form, $request)) {
            return $this->redirect(
                $this->generateUrl('party_created', ['listurl' => $party->getListurl()])
            );
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
