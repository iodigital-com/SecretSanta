<?php

namespace Intracto\SecretSantaBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Intracto\SecretSantaBundle\Entity\Entry;
use Intracto\SecretSantaBundle\Entity\EmailAddress;
use Intracto\SecretSantaBundle\Entity\EntryRepository;
use Intracto\SecretSantaBundle\Entity\WishlistItem;
use Intracto\SecretSantaBundle\Form\WishlistType;
use Intracto\SecretSantaBundle\Form\WishlistNewType;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Query\EntryReportQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntryController extends Controller
{
    /**
     * @DI\Inject("entry_repository")
     *
     * @var EntryRepository
     */
    public $entryRepository;

    /**
     * @DI\Inject("intracto_secret_santa.entry")
     *
     * @var EntryReportQuery
     */
    public $entryQuery;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     *
     * @var EntityManager
     */
    public $em;

    /**
     * @DI\Inject("validator")
     *
     * @var Validator
     */
    public $validator;

    /**
     * @DI\Inject("translator")
     *
     * @var TranslatorInterface;
     */
    public $translator;

    /**
     * @DI\Inject("intracto_secret_santa.mail")
     *
     * @var MailerService
     */
    public $mailerService;

    /** @var Entry */
    public $entry;

    /**
     * @Route("/entry/{url}", name="entry_view")
     * @Template()
     */
    public function indexAction(Request $request, $url)
    {
        $this->getEntry($url);

        if ($this->entry->getWishlist() !== null && $this->entry->getWishlist() != '') {
            $legacyWishlist = true;
            $form = $this->createForm(new WishlistType(), $this->entry);
        } else {
            $legacyWishlist = false;
            $form = $this->createForm(new WishlistNewType(), $this->entry);
        }

        // Log visit on first access
        if ($this->entry->getViewdate() === null) {
            $this->entry->setViewdate(new \DateTime());
            $this->em->flush($this->entry);
        }

        // Log ip address on first access
        if ($this->entry->getIp() === null) {
            $ip = $request->getClientIp();
            $this->entry->setIp($ip);
            $this->em->flush($this->entry);
        }

        if ('POST' === $request->getMethod()) {
            // get current items to compare against items later on
            $currentWishlistItems = new ArrayCollection();
            /** @var WishlistItem $item */
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
                    $this->em->persist($item);
                    // keep track of rank
                    if ($item->getRank() < $lastRank) {
                        $inOrder = false;
                    }
                    $lastRank = $item->getRank();
                }

                // remove entries not passed
                foreach ($currentWishlistItems as $item) {
                    if (!$newWishlistItems->contains($item)) {
                        $this->em->remove($item);
                    }
                }

                // For now assume that a save of entry means the list has changed
                $time_now = new \DateTime();
                $this->entry->setWishlistUpdated(true);
                $this->entry->setWishlistUpdatedTime($time_now);

                $this->em->persist($this->entry);
                $this->em->flush();

                if (!$request->isXmlHttpRequest()) {
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        $this->translator->trans('flashes.entry.wishlist_updated')
                    );

                    if (!$inOrder) {
                        // redirect to force refresh of form and entity
                        return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
                    }

                    if ($legacyWishlist && ($this->entry->getWishlist() === null || $this->entry->getWishlist() === '')) {
                        // started out with legacy, wishlist is empty now, reload page to switch to new wishlist
                        return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
                    }
                }

                if ($request->isXmlHttpRequest()) {
                    $return = ['responseCode' => 200, 'message' => 'Added!'];

                    return new JsonResponse($return);
                }
            }
        }

        $secret_santa = $this->entry->getEntry();

        if (!$request->isXmlHttpRequest()) {
            return [
                'entry' => $this->entry,
                'form' => $form->createView(),
                'secret_santa' => $secret_santa,
            ];
        }
    }

    /**
     * Retrieve entry by url.
     *
     * @param string $url
     *
     * @throws NotFoundHttpException
     *
     * @return bool
     */
    protected function getEntry($url)
    {
        $this->entry = $this->entryRepository->findOneByUrl($url);

        if (!is_object($this->entry)) {
            throw new NotFoundHttpException();
        }

        return true;
    }

    /**
     * @Route("/entry/edit-email/{listUrl}/{entryId}", name="entry_email_edit")
     * @Template()
     */
    public function editEmailAction(Request $request, $listUrl, $entryId)
    {
        /** @var Entry $entry */
        $entry = $this->entryRepository->find($entryId);

        if ($entry->getPool()->getListurl() === $listUrl) {
            $emailAddress = new EmailAddress($request->request->get('email'));
            $emailAddressErrors = $this->validator->validate($emailAddress);

            if (count($emailAddressErrors) > 0) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $this->translator->trans('flashes.entry.edit_email')
                );
            } else {
                $entry->setEmail((string) $emailAddress);
                $this->em->flush($entry);

                $this->mailerService->sendSecretSantaMailForEntry($entry);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->translator->trans('flashes.entry.saved_email')
                );
            }
        }

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
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
     * @Route("/poke/{url}/{entryId}", name="poke_buddy")
     * @Template()
     */
    public function pokeBuddyAction($url, $entryId)
    {
        $entry = $this->entryRepository->find($entryId);

        $this->mailerService->sendPokeMailToBuddy($entry);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->translator->trans('flashes.entry.poke_buddy')
        );

        return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
    }

    /**
     * @Route("/entry/remove/{listUrl}/{entryId}", name="entry_remove")
     * @Template()
     */
    public function removeEntryFromPoolAction($listUrl, $entryId)
    {
        $entry = $this->entryRepository->find($entryId);
        $pool = $entry->getPool()->getEntries();

        if (count($pool) <= 3) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->translator->trans('flashes.remove_participant.danger')
            );

            return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
        }

        if ($entry->isPoolAdmin()) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                $this->translator->trans('flashes.remove_participant.warning')
            );

            return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
        }

        $excludeCount = 0;

        foreach ($pool as $p) {
            if (count($p->getExcludedEntries()) > 0) {
                ++$excludeCount;
            }
        }

        if ($excludeCount > 0) {
            $this->get('session')->getFlashBag()->add(
                'warning',
                $this->translator->trans('flashes.remove_participant.excluded_entries')
            );

            return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
        }

        $secretSanta = $entry->getEntry();
        $buddyId = $this->entryQuery->findBuddyByEntryId($entryId);
        $buddy = $this->entryRepository->find($buddyId[0]['id']);

        $this->em->remove($entry);
        $this->em->flush();

        $buddy->setEntry($secretSanta);
        $this->em->persist($buddy);
        $this->em->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->translator->trans('flashes.remove_participant.success')
        );

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }
}
