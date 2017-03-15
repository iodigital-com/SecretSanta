<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Collections\ArrayCollection;
use Intracto\SecretSantaBundle\Entity\WishlistItem;
use Intracto\SecretSantaBundle\Form\Type\WishlistType;

class ShowController extends Controller
{
    /**
     * @Route("/entry/{url}", name="entry_view")
     * @Template("IntractoSecretSantaBundle:Entry/show:valid.html.twig")
     */
    public function showAction(Request $request, $url)
    {
        $entry = $this->get('entry_repository')->findOneByUrl($url);
        if ($entry === null) {
            throw new NotFoundHttpException();
        }

        if ($entry->getPool()->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Entry/show:expired.html.twig', [
                'entry' => $entry,
            ]);
        }

        $form = $this->createForm(WishlistType::class, $entry);

        // Log visit on first access
        if ($entry->getViewdate() === null) {
            $entry->setViewdate(new \DateTime());
            $this->get('doctrine.orm.entity_manager')->flush($entry);
        }

        // Log ip address on first access
        if ($entry->getIp() === null) {
            $ip = $request->getClientIp();
            $entry->setIp($ip);
            $this->get('doctrine.orm.entity_manager')->flush($entry);
        }

        if ('POST' === $request->getMethod()) {
            // get current items to compare against items later on
            $currentWishlistItems = new ArrayCollection();
            /** @var WishlistItem $item */
            foreach ($entry->getWishlistItems() as $item) {
                $currentWishlistItems->add($item);
            }

            $form->submit($request);

            if ($form->isValid()) {
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
                        return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
                    }
                }

                if ($request->isXmlHttpRequest()) {
                    $return = ['responseCode' => 200, 'message' => 'Added!'];

                    return new JsonResponse($return);
                }
            }
        }

        $secret_santa = $entry->getEntry();
        $eventDate = date_format($entry->getPool()->getEventdate(), 'Y-m-d');
        $oneWeekFromEventDate = date('Y-m-d', strtotime($eventDate.'- 1 week'));

        if (!$request->isXmlHttpRequest()) {
            return [
                'entry' => $entry,
                'form' => $form->createView(),
                'secret_santa' => $secret_santa,
                'oneWeekFromEventDate' => $oneWeekFromEventDate,
            ];
        }
    }
}
