<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Event\PoolEvent;
use Intracto\SecretSantaBundle\Event\PoolEvents;
use Intracto\SecretSantaBundle\Form\AddEntryType;
use Intracto\SecretSantaBundle\Form\ForgotLinkType;
use Intracto\SecretSantaBundle\Form\PoolExcludeEntryType;
use Intracto\SecretSantaBundle\Form\PoolType;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Entity\Entry;
use Intracto\SecretSantaBundle\Form\UpdatePoolDetailsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PoolController extends Controller
{
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
     * @param Pool    $pool
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function handlePoolCreation(Request $request, Pool $pool)
    {
        $form = $this->createForm(PoolType::class, $pool);

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

                $message = $this->get('translator')->trans('emails.created.message', [
                    '%amount%' => $pool->getAmount(),
                    '%eventdate%' => $dateFormatter->format($pool->getEventdate()->getTimestamp()),
                    '%location%' => $pool->getLocation(),
                    '%message%' => $pool->getMessage(),
                ]);

                $pool->setCreationDate(new \DateTime());
                $pool->setMessage($message);
                $pool->setLocale($request->getLocale());

                $this->get('doctrine.orm.entity_manager')->persist($pool);
                $this->get('doctrine.orm.entity_manager')->flush();

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
     * Retrieve pool by url.
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
        $this->pool = $this->get('pool_repository')->findOneByListurl($listurl);

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
            $this->get('event_dispatcher')->dispatch(
                PoolEvents::NEW_POOL_CREATED,
                new PoolEvent($this->pool)
            );
            
            return $this->redirect($this->generateUrl('pool_created', ['listUrl' => $this->pool->getListurl()]));
        }

        if ($this->pool->getEntries()->count() <= 3) {
            $this->pool->setCreated(true);
            $this->get('doctrine.orm.entity_manager')->persist($this->pool);

            $this->get('intracto_secret_santa.entry_service')->shuffleEntries($this->pool);

            $this->get('doctrine.orm.entity_manager')->flush();

            $this->get('event_dispatcher')->dispatch(
                PoolEvents::NEW_POOL_CREATED,
                new PoolEvent($this->pool)
            );

            return $this->redirect($this->generateUrl('pool_created', ['listUrl' => $this->pool->getListurl()]));
        }

        $form = $this->createForm(new PoolExcludeEntryType(), $this->pool);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->pool->setCreated(true);
                $this->get('doctrine.orm.entity_manager')->persist($this->pool);

                $this->get('intracto_secret_santa.entry_service')->shuffleEntries($this->pool);

                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('event_dispatcher')->dispatch(
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
                $this->get('translator')->trans('flashes.manage.email_validated')
            );

            $this->get('intracto_secret_santa.mail')->sendSecretSantaMailsForPool($this->pool);
        }

        $eventDate = date_format($this->pool->getEventdate(), 'Y-m-d');
        $oneWeekFromEventDate = date('Y-m-d', strtotime($eventDate.'- 1 week'));

        $newEntry = new Entry();
        $updatePool = $this->pool;

        $addEntryForm = $this->createForm(AddEntryType::class, $newEntry);
        $updatePoolDetailsForm = $this->createForm(UpdatePoolDetailsType::class, $updatePool);

        if ($request->getMethod('POST')) {
            $addEntryForm->handleRequest($request);
            $updatePoolDetailsForm->handleRequest($request);

            if ($addEntryForm->isSubmitted()) {
                if ($addEntryForm->isValid()) {
                    if (date('Y-m-d') > $oneWeekFromEventDate) {
                        $this->get('session')->getFlashBag()->add(
                            'warning',
                            $this->get('translator')->trans('flashes.modify_list.warning')
                        );

                        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
                    }

                    $newEntry->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
                    $newEntry->setPool($this->pool);

                    $this->get('doctrine.orm.entity_manager')->persist($newEntry);
                    $this->get('doctrine.orm.entity_manager')->flush($newEntry);

                    $adminId = $this->get('intracto_secret_santa.entry')->findAdminIdByPoolId($this->pool->getId());
                    $admin = $this->get('entry_repository')->findOneById($adminId[0]['id']);
                    $adminMatch = $admin->getEntry();

                    $admin->setEntry($newEntry);
                    $this->get('doctrine.orm.entity_manager')->persist($admin);
                    $this->get('doctrine.orm.entity_manager')->flush($admin);

                    $newEntry->setEntry($adminMatch);
                    $this->get('doctrine.orm.entity_manager')->persist($newEntry);
                    $this->get('doctrine.orm.entity_manager')->flush();

                    $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForEntry($admin);
                    $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForEntry($newEntry);

                    $this->get('session')->getFlashBag()->add(
                        'success',
                        $this->get('translator')->trans('flashes.add_participant.success')
                    );

                    return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'danger',
                        $this->get('translator')->trans('flashes.add_participant.danger')
                    );
                }
            }

            if ($updatePoolDetailsForm->isSubmitted()) {
                if ($updatePoolDetailsForm->isValid()) {
                    $time_now = new \DateTime();

                    $updatePool->setDetailsUpdated(true);
                    $updatePool->setDetailsUpdatedTime($time_now);

                    $this->get('doctrine.orm.entity_manager')->persist($updatePool);
                    $this->get('doctrine.orm.entity_manager')->flush();

                    $this->get('session')->getFlashBag()->add(
                        'success',
                        $this->get('translator')->trans('flashes.updated_party.success')
                    );

                    return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'danger',
                        $this->get('translator')->trans('flashes.updated_party.danger')
                    );
                }
            }
        }

        return [
            'addEntryForm' => $addEntryForm->createView(),
            'updatePoolDetailsForm' => $updatePoolDetailsForm->createView(),
            'pool' => $this->pool,
            'oneWeekFromEventDate' => $oneWeekFromEventDate,
            'delete_pool_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_pool'),
            'expose_pool_csrf_token' => $this->get('security.csrf.token_manager')->getToken('expose_pool'),
            'expose_pool_wishlists_csrf_token' => $this->get('security.csrf.token_manager')->getToken('expose_wishlists'),
            'delete_participant_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_participant'),
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
        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($this->get('translator')->trans('delete.phrase_to_type')));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('flashes.delete.not_deleted')
            );

            return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
        }

        $this->getPool($listUrl);

        $this->get('doctrine.orm.entity_manager')->remove($this->pool);
        $this->get('doctrine.orm.entity_manager')->flush();
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

        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($this->get('translator')->trans('expose.phrase_to_type')));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.expose.not_exposed')
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('flashes.expose.exposed')
            );
        }

        /* Tell db pool has been exposed */
        $this->getPool($listUrl);
        $this->pool->expose();

        /* Save db changes */
        $this->get('doctrine.orm.entity_manager')->flush();

        /* Mail pool owner the pool matches */
        $this->get('intracto_secret_santa.mail')->sendPoolMatchesToAdmin($this->pool);

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

        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($this->get('translator')->trans('expose_wishlists.phrase_to_type')));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('flashes.expose_wishlists.not_exposed')
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('flashes.expose_wishlists.exposed')
            );
        }

        $this->getPool($listUrl);
        $this->pool->exposeWishlists();

        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('intracto_secret_santa.mail')->sendAllWishlistsToAdmin($this->pool);

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }

    /**
     * @Route("/resend/{listUrl}/{entryId}", name="pool_resend")
     * @Template("IntractoSecretSantaBundle:Pool:manage.html.twig")
     */
    public function resendAction($listUrl, $entryId)
    {
        $entry = $this->get('entry_repository')->find($entryId);

        if (!is_object($entry)) {
            throw new NotFoundHttpException();
        }

        if ($entry->getPool()->getListUrl() !== $listUrl) {
            throw new NotFoundHttpException();
        }

        $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForEntry($entry);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.resend.resent', ['%email%' => $entry->getName()])
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
                if ($this->get('intracto_secret_santa.mail')->sendForgotManageLinkMail($form->getData()['email'])) {
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
        $results = $this->get('intracto_secret_santa.entry')->fetchDataForPoolUpdateMail($listUrl);
        $this->getPool($listUrl);

        $this->get('intracto_secret_santa.mail')->sendPoolUpdateMailForPool($this->pool, $results);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.pool_update.success')
        );

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $this->pool->getListurl()]));
    }

    /**
     * @Route("/download-csv-template", name="download_csv_template")
     * @Template()
     */
    public function downloadCSVTemplateAction()
    {
        $path = $this->get('kernel')->getRootDir().'/../src/Intracto/SecretSantaBundle/Resources/public/downloads/templateCSVSecretSantaOrganizer.csv';
        $content = file_get_contents($path);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="templateCSVSecretSantaOrganizer.csv"');

        $response->setContent($content);

        return $response;
    }
}
