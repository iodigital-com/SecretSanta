<?php

declare(strict_types=1);

namespace App\Controller\Party;

use App\Entity\Party;
use App\Form\Handler\Exception\RateLimitExceededException;
use App\Form\Handler\PartyFormHandler;
use App\Form\Type\PartyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PartyController extends AbstractController
{
    #[Route('/{_locale}/party/create', name: 'create_party')]
    public function createAction(Request $request, PartyFormHandler $handler): RedirectResponse|Response
    {
        if ('POST' != $request->getMethod()) {
            return $this->redirectToRoute('homepage');
        }

        $data = $this->handlePartyCreation($request, new Party(), $handler);

        if (is_array($data)) {
            return $this->render('Party/create.html.twig', $data);
        }

        return $data;
    }

    #[Route('/{_locale}/created/{listurl}', name: 'party_created', methods: ['GET'])]
    public function createdAction(Party $party): Response
    {
        return $this->render('Party/created.html.twig', [
            'party' => $party,
        ]);
    }

    #[Route('/{_locale}/reuse/{listurl}', name: 'party_reuse', methods: ['GET'])]
    public function reuseAction(Request $request, Party $party, PartyFormHandler $handler): Response
    {
        $originalAmountOfParticipants = $party->getParticipants()->count();
        list($party, $countHashed) = $party->createNewPartyForReuse();

        $data = $this->handlePartyCreation($request, $party, $handler);
        $data['countHashed'] = $countHashed;
        $data['originalAmountOfParticipants'] = $originalAmountOfParticipants;

        return $this->render('Party/create.html.twig', $data);
    }

    #[Route('/{_locale}/delete/{listurl}', name: 'party_delete', methods: ['POST'])]
    public function deleteAction(Request $request, Party $party, TranslatorInterface $translator, EntityManagerInterface $em): RedirectResponse|Response
    {
        $correctCsrfToken = $this->isCsrfTokenValid('delete_party', $request->get('csrf_token'));
        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($translator->trans('party_manage_valid.delete.phrase_to_type')));

        if (false === $correctConfirmation || false === $correctCsrfToken) {
            $this->addFlash(
                'error',
                $translator->trans('flashes.party.not_deleted')
            );

            return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
        }

        $em->remove($party);
        $em->flush();

        return $this->render('Party/deleted.html.twig');
    }

    private function handlePartyCreation(Request $request, Party $party, PartyFormHandler $handler): RedirectResponse|array
    {
        $form = $this->createForm(PartyType::class, $party, [
            'action' => $this->generateUrl('create_party'),
        ]);

        $rateLimitReached = false;
        try {
            $ignoreRateLimit = in_array($this->getParameter('kernel.environment'), ['dev', 'test'], true);

            if ($handler->handle($form, $request, $ignoreRateLimit)) {
                return $this->redirectToRoute('party_created', ['listurl' => $party->getListurl()]);
            }
        } catch (RateLimitExceededException) {
            $rateLimitReached = true;
        }

        return [
            'form' => $form->createView(),
            'rateLimitReached' => $rateLimitReached,
        ];
    }
}
