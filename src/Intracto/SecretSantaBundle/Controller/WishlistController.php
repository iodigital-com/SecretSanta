<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Entity\WishlistItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\WishlistType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class WishlistController extends Controller
{
    /**
     * @Route("/wishlists/show/{listurl}", name="wishlist_show_all")
     * @Template("IntractoSecretSantaBundle:Wishlist:showAll.html.twig")
     */
    public function showAllAction(Party $party)
    {
        return [
            'party' => $party,
        ];
    }

    /**
     * @Route("/wishlist/update/{url}", name="wishlist_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, Participant $participant)
    {
        $wishlistForm = $this->createForm(WishlistType::class, $participant);

        // get current items to compare against items later on
        $currentWishlistItems = new ArrayCollection();
        /** @var WishlistItem $item */
        foreach ($participant->getWishlistItems() as $item) {
            $currentWishlistItems->add($item);
        }

        $wishlistForm->handleRequest($request);

        if (!$wishlistForm->isValid()) {
            $return = ['responseCode' => 400, 'message' => 'Form invalid!'];

            return new JsonResponse($return);
        }

        // save participants passed and check rank
        $inOrder = true;
        $lastRank = 0;
        $newWishlistItems = $participant->getWishlistItems();

        foreach ($newWishlistItems as $item) {
            $item->setParticipant($participant);
            $this->get('doctrine.orm.entity_manager')->persist($item);
            // keep track of rank
            if ($item->getRank() < $lastRank) {
                $inOrder = false;
            }
            $lastRank = $item->getRank();
        }

        // remove participants not passed
        foreach ($currentWishlistItems as $item) {
            if (!$newWishlistItems->contains($item)) {
                $this->get('doctrine.orm.entity_manager')->remove($item);
            }
        }

        // For now assume that a save of participant means the list has changed
        $time_now = new \DateTime();
        $participant->setWishlistUpdated(true);
        $participant->setWishlistUpdatedTime($time_now);

        $this->get('doctrine.orm.entity_manager')->persist($participant);
        $this->get('doctrine.orm.entity_manager')->flush();

        if (!$request->isXmlHttpRequest()) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('flashes.participant.wishlist_updated')
            );

            if (!$inOrder) {
                // redirect to force refresh of form and entity
                return $this->redirect($this->generateUrl('participant_view', ['url' => $participant->getUrl()]));
            }
        }

        if ($request->isXmlHttpRequest()) {
            $return = ['responseCode' => 200, 'message' => 'Added!'];

            return new JsonResponse($return);
        }
    }
}
