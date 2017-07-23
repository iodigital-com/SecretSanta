<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Party;

use Intracto\SecretSantaBundle\Form\Handler\PartyFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Form\Type\PartyType;
use Symfony\Component\Translation\TranslatorInterface;

class PartyController extends AbstractController
{
    /**
     * @Route("/party/create", name="create_party")
     * @Template("IntractoSecretSantaBundle:Party:create.html.twig")
     * @Method("POST")
     */
    public function createAction(Request $request, PartyFormHandler $handler)
    {
        return $this->handlePartyCreation($request, new Party(), $handler);
    }

    /**
     * @Route("/created/{listurl}", name="party_created")
     * @Template("IntractoSecretSantaBundle:Party:created.html.twig")
     * @Method("GET")
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
     * @Method("GET")
     */
    public function reuseAction(Request $request, Party $party, PartyFormHandler $handler)
    {
        $party = $party->createNewPartyForReuse();

        return $this->handlePartyCreation($request, $party, $handler);
    }

    /**
     * @Route("/delete/{listurl}", name="party_delete")
     * @Template("IntractoSecretSantaBundle:Party:deleted.html.twig")
     * @Method("POST")
     */
    public function deleteAction(Request $request, Party $party, TranslatorInterface $translator)
    {
        $correctCsrfToken = $this->isCsrfTokenValid('delete_party', $request->get('csrf_token'));
        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($translator->trans('party_manage_valid.delete.phrase_to_type')));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->addFlash(
                'error',
                $translator->trans('flashes.party.not_deleted')
            );

            return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
        }

        $this->getDoctrine()->getManager()->remove($party);
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * @param Request $request
     * @param Party   $party
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function handlePartyCreation(Request $request, Party $party, PartyFormHandler $handler)
    {
        $form = $this->createForm(PartyType::class, $party, [
            'action' => $this->generateUrl('create_party'),
        ]);

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('party_created', ['listurl' => $party->getListurl()]);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
