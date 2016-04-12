<?php

namespace Intracto\SecretSantaBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Intracto\SecretSantaBundle\Entity\EntryService;
use Intracto\SecretSantaBundle\Event\PoolEvent;
use Intracto\SecretSantaBundle\Event\PoolEvents;
use Intracto\SecretSantaBundle\Form\AddEntryType;
use Intracto\SecretSantaBundle\Form\ForgotLinkType;
use Intracto\SecretSantaBundle\Form\PoolExcludeEntryType;
use Intracto\SecretSantaBundle\Form\PoolType;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Entity\Entry;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Query\EntryReportQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;

class PoolController extends Controller
{
    /**
     * @DI\Inject("%admin_email%")
     */
    public $adminEmail;

    /**
     * @DI\Inject("pool_repository")
     *
     * @var EntityRepository
     */
    public $poolRepository;

    /**
     * @DI\Inject("entry_repository")
     *
     * @var EntityRepository
     */
    public $entryRepository;

    /**
     * @DI\Inject("event_dispatcher")
     *
     * @var EventDispatcherInterface
     */
    public $eventDispatcher;

    /**
     * @DI\Inject("intracto_secret_santa.entry_service")
     *
     * @var EntryService
     */
    public $entryService;

    /**
     * @DI\Inject("intracto_secret_santa.mail")
     *
     * @var MailerService
     */
    public $mailerService;

    /**
     * @DI\Inject("intracto_secret_santa.entry")
     *
     * @var EntryReportQuery
     */
    public $entryQuery;

    /**
     * @DI\Inject("translator")
     *
     * @var TranslatorInterface
     */
    public $translator;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     *
     * @var EntityManager
     */
    public $em;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var Entry
     */
    private $entry;

    /**
     * @Route("/", name="pool_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $pool = new Pool();

        return $this->handlePoolCreation($request, $pool);
    }

    /**
     * @param Request $request
     * @param Pool $pool
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function handlePoolCreation(Request $request, Pool $pool)
    {
        $form = $this->createForm(new PoolType(), $pool);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                foreach ($pool->getEntries() as $entry) {
                    $entry->setPool($pool);
                }

                $dateFormatter = \IntlDateFormatter::create(
                    $request->getLocale(),
                    \IntlDateFormatter::MEDIUM,
                    \IntlDateFormatter::NONE
                );

                $message = $this->translator->trans('emails.created.message', [
                    '%amount%' => $pool->getAmount(),
                    '%eventdate%' => $dateFormatter->format($pool->getEventdate()->getTimestamp()),
                    '%location%' => $pool->getLocation(),
                    '%message%' => $pool->getMessage(),
                ]);

                $pool->setCreationDate(new \DateTime());
                $pool->setMessage($message);
                $pool->setLocale($request->getLocale());
                $this->em->persist($pool);
                $this->em->flush();

                return $this->redirect($this->generateUrl('pool_exclude', ['listUrl' => $pool->getListurl()]));
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/reuse/{listUrl}", name="pool_reuse")
     * @Template("IntractoSecretSantaBundle:Pool:create.html.twig")
     */
    public function reuseAction(Request $request, $listUrl)
    {
        $this->getPool($listUrl);
        $pool = $this->pool->createNewPoolForReuse();

        return $this->handlePoolCreation($request, $pool);
    }

    /**
     * Retrieve pool by url
     *
     * @param $listurl
     *
     * @throws NotFoundHttpException
     *
     * @internal param string $url
     *
     * @return bool
     */
    protected function getPool($listurl)
    {
        $this->pool = $this->poolRepository->findOneByListurl($listurl);

        if (!is_object($this->pool)) {
            throw new NotFoundHttpException();
        }

        return true;
    }

    /**
     * @Route("/exclude/{listUrl}", name="pool_exclude")
     * @Template()
     */
    public function excludeAction(Request $request, $listUrl)
    {
        $this->getPool($listUrl);

        if ($this->pool->getCreated()) {
            return $this->redirect($this->generateUrl('pool_created', ['listUrl' => $this->pool->getListurl()]));
        }

        $form = $this->createForm(new PoolExcludeEntryType(), $this->pool);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->pool->setCreated(true);
                $this->em->persist($this->pool);

                $this->entryService->shuffleEntries($this->pool);

                $this->em->flush();

                $this->eventDispatcher->dispatch(
                    PoolEvents::NEW_POOL_CREATED,
                    new PoolEvent($this->pool)
                );

                return $this->redirect($this->generateUrl('pool_created', ['listUrl' => $this->pool->getListurl()]));
            }
        }

        return [
            'form' => $form->createView(),
            'pool' => $this->pool,
        ];
    }

    /**
     * @Route("/created/{listUrl}", name="pool_created")
     * @Template()
     */
    public function createdAction($listUrl)
    {
        $this->getPool($listUrl);
        if (!$this->pool->getCreated()) {
            return $this->redirect($this->generateUrl('pool_exclude', ['listUrl' => $this->pool->getListurl()]));
        }

        return [
            'pool' => $this->pool,
        ];
    }

    /**
     * @Route("/manage/{listUrl}", name="pool_manage")
     * @Template()
     */
    public function manageAction(Request $request, $listUrl)
    {
        $this->getPool($listUrl);
        if (!$this->pool->getCreated()) {
            return $this->redirect($this->generateUrl('pool_exclude', ['listUrl' => $this->pool->getListurl()]));
        }

        if ($this->pool->getSentdate() === null) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->translator->trans('flashes.manage.email_validated')
            );

            $this->mailerService->sendSecretSantaMailsForPool($this->pool);
        }

        $newEntry = new Entry();

        $form = $this->createForm(new AddEntryType(), $newEntry);

        if ($request->getMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $newEntry->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
                    $newEntry->setPool($this->pool);

                    $this->em->persist($newEntry);
                    $this->em->flush($newEntry);

                    $adminId = $this->entryQuery->findAdminIdByPoolId($this->pool->getId());
                    $admin = $this->entryRepository->findOneById($adminId[0]['id']);
                    $adminMatch = $admin->getEntry();

                    $admin->setEntry($newEntry);
                    $this->em->persist($admin);
                    $this->em->flush($admin);

                    $newEntry->setEntry($adminMatch);
                    $this->em->persist($newEntry);
                    $this->em->flush();

                    $this->mailerService->sendSecretSantaMailForEntry($newEntry);

                    $this->get('session')->getFlashBag()->add(
                        'success',
                        $this->translator->trans('flashes.add_participant.success')
                    );

                    return $this->redirect($this->generateUrl('pool_manage', array('listUrl' => $listUrl)));
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'danger',
                        $this->translator->trans('flashes.add_participant.danger')
                    );
                }
            }
        }

        return [
            'form' => $form->createView(),
            'pool' => $this->pool,
            'delete_pool_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_pool'),
            'expose_pool_csrf_token' => $this->get('security.csrf.token_manager')->getToken('expose_pool'),
            'expose_pool_wishlists_csrf_token' => $this->get('security.csrf.token_manager')->getToken('expose_wishlists'),
        ];
    }

    /**
     * @Route("/delete/{listUrl}", name="pool_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $listUrl)
    {
        $correctCsrfToken = $this->isCsrfTokenValid(
            'delete_pool',
            $request->get('csrf_token')
        );
        $correctConfirmation = ($request->get('confirmation') === $this->translator->trans('delete.phrase_to_type'));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->translator->trans('flashes.delete.not_deleted')
            );

            return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
        }

        $this->getPool($listUrl);

        $this->em->remove($this->pool);
        $this->em->flush();
    }

    /**
     * @Route("/expose/{listUrl}", name="pool_expose")
     * @Template()
     */
    public function exposeAction(Request $request, $listUrl)
    {
        $correctCsrfToken = $this->isCsrfTokenValid(
            'expose_pool',
            $request->get('csrf_token')
        );

        $correctConfirmation = ($request->get('confirmation') === $this->translator->trans('expose.phrase_to_type'));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->translator->trans('flashes.expose.not_exposed')
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->translator->trans('flashes.expose.exposed')
            );
        }

        /* Tell db pool has been exposed */
        $this->getPool($listUrl);
        $this->pool->expose();

        /* Save db changes */
        $this->em->flush();

        /* Mail pool owner the pool matches */
        $this->mailerService->sendPoolMatchesToAdmin($this->pool);

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }

    /**
     * @Route("/expose_wishlists/{listUrl}", name="pool_expose_wishlists")
     * @Template()
     */
    public function exposeWishlistsAction(Request $request, $listUrl)
    {
        $correctCsrfToken = $this->isCsrfTokenValid(
            'expose_wishlists',
            $request->get('csrf_token')
        );

        $correctConfirmation = ($request->get('confirmation') === $this->translator->trans('expose_wishlists.phrase_to_type'));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->translator->trans('flashes.expose_wishlists.not_exposed')
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->translator->trans('flashes.expose_wishlists.exposed')
            );
        }

        $this->getPool($listUrl);
        $this->pool->exposeWishlists();

        $this->em->flush();

        $this->mailerService->sendAllWishlistsToAdmin($this->pool);

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }

    /**
     * @Route("/resend/{listUrl}/{entryId}", name="pool_resend")
     * @Template("IntractoSecretSantaBundle:Pool:manage.html.twig")
     */
    public function resendAction($listUrl, $entryId)
    {
        $entry = $this->entryRepository->find($entryId);

        if (!is_object($entry)) {
            throw new NotFoundHttpException();
        }

        if ($entry->getPool()->getListUrl() !== $listUrl) {
            throw new NotFoundHttpException();
        }

        $this->mailerService->sendSecretSantaMailForEntry($entry);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->translator->trans('flashes.resend.resent', ['%email%' => $entry->getName()])
        );

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }

    /**
     * @Route("/forgot-link", name="pool_forgot_manage_link")
     * @Template("IntractoSecretSantaBundle:Pool:forgotLink.html.twig")
     */
    public function forgotLinkAction(Request $request)
    {
        $form = $this->createForm(new ForgotLinkType());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($this->mailerService->sendForgotManageLinkMail($form->getData()['email'])) {
                    $feedback = [
                        'type' => 'success',
                        'message' => $this->get('translator')->trans('flashes.forgot_manage_link.success'),
                    ];
                } else {
                    $feedback = [
                        'type' => 'error',
                        'message' => $this->get('translator')->trans('flashes.forgot_manage_link.error'),
                    ];
                }

                $this->addFlash($feedback['type'], $feedback['message']);
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/pool-update/{listUrl}", name="pool_update")
     * @Template()
     */
    public function sendPoolUpdateAction($listUrl)
    {
        $results = $this->entryQuery->fetchDataForPoolUpdateMail($listUrl);
        $this->getPool($listUrl);

        $this->mailerService->sendPoolUpdateMailForPool($this->pool, $results);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->translator->trans('flashes.pool_update.success')
        );

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $this->pool->getListurl()]));
    }
}