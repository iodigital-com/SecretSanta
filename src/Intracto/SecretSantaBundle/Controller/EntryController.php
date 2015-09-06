<?php

namespace Intracto\SecretSantaBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Intracto\SecretSantaBundle\Form\WishlistType;
use Intracto\SecretSantaBundle\Form\WishlistNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Entity\Entry;
use Intracto\SecretSantaBundle\Entity\EmailAddress;

class EntryController extends Controller
{
    /**
     * @DI\Inject("entry_repository")
     * @var \Doctrine\ORM\EntityRepository
     */
    public $entryRepository;

    /** @var Entry */
    public $entry;

    /**
     * @Route("/entry/{url}", name="entry_view")
     * @Template()
     */
    public function indexAction(Request $request, $url)
    {
        $em = $this->getDoctrine()->getManager();
        $this->getEntry($url);

        if ($this->entry->getWishlist() !== null && $this->entry->getWishlist() != "") {
            $legacyWishlist = true;
            $form = $this->createForm(new WishlistType(), $this->entry);
        } else {
            $legacyWishlist = false;
            $form = $this->createForm(new WishlistNewType(), $this->entry);
        }

        // Log visit on first access
        if ($this->entry->getViewdate() === null) {
            $this->entry->setViewdate(new \DateTime());
            $em->flush($this->entry);
        }

        if ('POST' === $request->getMethod()) {
            // get current items to compare against items later on
            $currentWishlistItems = new ArrayCollection();
            foreach ($this->entry->getWishlistItems() as $item) {
                $currentWishlistItems->add($item);
            }
            $form->submit($request);
            if ($form->isValid()) {
                // save entries passed and check rank
                $inOrder = true;
                $lastRank = 0;
                $newWishlistItems = $this->entry->getWishlistItems();

                foreach ($newWishlistItems as $item) {
                    $item->setEntry($this->entry);
                    $em->persist($item);
                    // keep track of rank
                    if ($item->getRank() < $lastRank) {
                        $inOrder = false;
                    }
                    $lastRank = $item->getRank();
                }

                // remove entries not passed
                foreach ($currentWishlistItems as $item) {
                    if (!$newWishlistItems->contains($item)) {
                        $em->remove($item);
                    }
                }

                // For now assume that a save of entry means the list has changed
                $this->entry->setWishlistUpdated(true);

                $em->persist($this->entry);
                $em->flush();

                $translator = $this->get('translator');
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $translator->trans('flashes.entry.wishlist_updated')
                );

                if (!$inOrder) {
                    // redirect to force refresh of form and entity
                    return $this->redirect($this->generateUrl('entry_view', array('url' => $url)));
                }

                if ($legacyWishlist && ($this->entry->getWishlist() === null || $this->entry->getWishlist() === "")) {
                    // started out with legacy, wishlist is empty now, reload page to switch to new wishlist
                    return $this->redirect($this->generateUrl('entry_view', array('url' => $url)));
                }
            }
        }
        $secret_santa = $this->entry->getEntry();

        return array(
            'entry' => $this->entry,
            'form' => $form->createView(),
            'secret_santa' => $secret_santa,
        );
    }

    /**
     * @Route("/entry/edit-email/{listUrl}/{entryId}", name="entry_email_edit")
     * @Template()
     */
    public function editEmailAction($listUrl, $entryId)
    {
        /** @var Entry $entry */
        $entry = $this->entryRepository->find($entryId);

        if ($entry->getPool()->getListurl() === $listUrl) {
            /** @var \Symfony\Component\Validator\Validator $validatorService */
            $validatorService = $this->get('validator');

            $emailAddress = new EmailAddress($this->getRequest()->request->get('email'));
            $emailAddressErrors = $validatorService->validate($emailAddress);

            if (count($emailAddressErrors) > 0) {
                $translator = $this->get('translator');
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $translator->trans('flashes.entry.edit_email')
                );
            } else {
                $em = $this->getDoctrine()->getManager();
                $entry->setEmail((string) $emailAddress);
                $em->flush($entry);

                $entryService = $this->get('intracto_secret_santa.entry_service');
                $entryService->sendSecretSantaMailForEntry($entry);

                $translator = $this->get('translator');
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $translator->trans('flashes.entry.saved_email')
                );
            }
        }

        return $this->redirect($this->generateUrl('pool_manage', array('listUrl' => $listUrl)));
    }

    /**
     * @Route("/dump-entries", name="dump_entries")
     * @Template()
     * @Secure(roles="ROLE_ADWORDS")
     */
    public function dumpEntriesAction()
    {
        $startCrawling = new \DateTime();
        $startCrawling->sub(new \DateInterval('P4M'));

        return ['entries' => $this->entryRepository->findAfter($startCrawling)];
    }

    /**
     * Retrieve entry by url
     *
     * @param string $url
     *
     * @throws NotFoundHttpException
     * @return boolean
     */
    protected function getEntry($url)
    {
        $this->entry = $this->entryRepository->findOneByUrl($url);

        if (!is_object($this->entry)) {
            throw new NotFoundHttpException();
        }

        return true;
    }
}
