<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Form\Handler\WishlistFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\WishlistType;
use Symfony\Component\HttpFoundation\JsonResponse;

class WishlistController extends AbstractController
{
    /**
     * @Route("/wishlists/show/{wishlistsurl}", name="wishlist_show_all")
     * @Template("IntractoSecretSantaBundle:Wishlist:showAll.html.twig")
     * @Method("GET")
     */
    public function showAllAction(Party $party) : array
    {
        return ['party' => $party];
    }

    /**
     * @Route("/wishlist/update/{url}", name="wishlist_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, Participant $participant, WishlistFormHandler $handler) : JsonResponse
    {
        $wishlistForm = $this->createForm(WishlistType::class, $participant, ['validation_groups' => 'WishlistItem']);

        if ($handler->handle($wishlistForm, $request)) {
            return new JsonResponse(['success' => true, 'message' => 'Added!']);
        }

        return new JsonResponse(['success' => false, 'message' => 'An error occurred.']);
    }
}
