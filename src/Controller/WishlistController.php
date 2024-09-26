<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Party;
use App\Form\Handler\WishlistFormHandler;
use App\Form\Type\WishlistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishlistController extends AbstractController
{
    #[Route('/{_locale}/wishlists/show/{wishlistsurl}', name: 'wishlist_show_all', methods: ['GET'])]
    public function showAllAction(Request $request, Party $party): Response
    {
        $admin = false;

        $referrer = $request->headers->get('referer');
        if ($referrer && preg_match("/\/manage\/[[:alnum:]]{31}/", $referrer)) {
            $admin = true;
        }

        return $this->render('Wishlist/showAll.html.twig', [
            'party' => $party,
            'admin' => $admin,
        ]);
    }

    #[Route('/{_locale}/wishlist/update/{url}', name: 'wishlist_update', methods: ['POST'])]
    public function updateAction(Request $request, Participant $participant, WishlistFormHandler $handler): JsonResponse
    {
        $wishlistForm = $this->createForm(WishlistType::class, $participant, ['validation_groups' => 'WishlistItem']);

        if ($handler->handle($wishlistForm, $request)) {
            return new JsonResponse(['success' => true, 'message' => 'Added!']);
        }

        return new JsonResponse(['success' => false, 'message' => 'An error occurred.']);
    }
}
