<?php

namespace Intracto\SecretSantaBundle\Controller;

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
     * @Route("/wishlists/show/{listUrl}", name="wishlist_show_all")
     * @Template("IntractoSecretSantaBundle:Wishlist:showAll.html.twig")
     */
    public function showAllAction($listUrl)
    {
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if ($pool === false) {
            throw new NotFoundHttpException();
        }

        return [
            'pool' => $pool,
        ];
    }

    /**
     * @Route("/wishlist/update/{url}", name="wishlist_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, $url)
    {
        $entry = $this->get('entry_repository')->findOneByUrl($url);
        if ($entry === null) {
            throw new NotFoundHttpException();
        }
        $wishlistForm = $this->createForm(WishlistType::class, $entry);

        // get current items to compare against items later on
        $currentWishlistItems = new ArrayCollection();
        /** @var WishlistItem $item */
        foreach ($entry->getWishlistItems() as $item) {
            $currentWishlistItems->add($item);
        }

        $wishlistForm->submit($request);

        if ($wishlistForm->isValid()) {
            // save entries passed and check rank
            $inOrder = true;
            $lastRank = 0;
            $newWishlistItems = $entry->getWishlistItems();

            foreach ($newWishlistItems as $item) {
                $item->setEntry($entry);
                $this->get('doctrine.orm.entity_manager')->persist($item);
                // keep track of rank
                if ($item->getRank() < $lastRank) {
                    $inOrder = false;
                }
                $lastRank = $item->getRank();
            }

            // remove entries not passed
            foreach ($currentWishlistItems as $item) {
                if (!$newWishlistItems->contains($item)) {
                    $this->get('doctrine.orm.entity_manager')->remove($item);
                }
            }

            // For now assume that a save of entry means the list has changed
            $time_now = new \DateTime();
            $entry->setWishlistUpdated(true);
            $entry->setWishlistUpdatedTime($time_now);

            $this->get('doctrine.orm.entity_manager')->persist($entry);
            $this->get('doctrine.orm.entity_manager')->flush();

            if (!$request->isXmlHttpRequest()) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('flashes.entry.wishlist_updated')
                );

                if (!$inOrder) {
                    // redirect to force refresh of form and entity
                    //return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
                    return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
                }
            }

            if ($request->isXmlHttpRequest()) {
                $return = ['responseCode' => 200, 'message' => 'Added!'];

                return new JsonResponse($return);
            }
        }
    }

}
