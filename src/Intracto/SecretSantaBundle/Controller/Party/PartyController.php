<?php

namespace Intracto\SecretSantaBundle\Controller\Party;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        return $this->handlePartyCreation(
            $request,
            new Party()
        );
    }

    /**
     * @Route("/created/{listUrl}", name="party_created")
     * @Template("IntractoSecretSantaBundle:Party:created.html.twig")
     */
    public function createdAction($listUrl)
    {
        $party = $this->getParty($listUrl);

        return [
            'party' => $party,
        ];
    }

    /**
     * @Route("/reuse/{listUrl}", name="party_reuse")
     * @Template("IntractoSecretSantaBundle:Party:create.html.twig")
     */
    public function reuseAction(Request $request, $listUrl)
    {
        $party = $this->getParty($listUrl);
        $party = $party->createNewPartyForReuse();

        return $this->handlePartyCreation($request, $party);
    }

    /**
     * @Route("/delete/{listUrl}", name="party_delete")
     * @Template("IntractoSecretSantaBundle:Party:deleted.html.twig")
     */
    public function deleteAction(Request $request, $listUrl)
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

            return $this->redirect($this->generateUrl('party_manage', ['listUrl' => $listUrl]));
        }

        $party = $this->getParty($listUrl);

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
        $form = $this->createForm(
            PartyType::class,
            $party,
            [
                'action' => $this->generateUrl('create_party'),
            ]
        );

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                foreach ($party->getParticipants() as $participant) {
                    $participant->setParty($party);
                }

                $party->setCreationDate(new \DateTime());
                $party->setLocale($request->getLocale());

                $this->get('doctrine.orm.entity_manager')->persist($party);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('intracto_secret_santa.mailer')->sendPendingConfirmationMail($party);

                return $this->redirect($this->generateUrl('party_created', ['listUrl' => $party->getListurl()]));
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Retrieve party by url.
     *
     * @param $listUrl
     *
     * @return Party
     *
     * @throws NotFoundHttpException
     */
    private function getParty($listUrl)
    {
        /** @var \Intracto\SecretSantaBundle\Entity\PartyRepository $party */
        $party = $this->get('intracto_secret_santa.repository.party')->findOneByListurl($listUrl);
        if ($party === null) {
            throw new NotFoundHttpException();
        }

        return $party;
    }
}
