<?php

namespace Intracto\SecretSantaBundle\Controller\Pool;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Form\PoolExcludeEntryType;
use Intracto\SecretSantaBundle\Form\PoolType;
use Intracto\SecretSantaBundle\Entity\Pool;

class PoolController extends Controller
{
    /**
     * @Route("/pool/create", name="create_pool")
     * @Method("POST")
     * @Template("IntractoSecretSantaBundle:Pool:create.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->handlePoolCreation(
            $request,
            new Pool()
        );
    }

    /**
     * @Route("/created/{listUrl}", name="pool_created")
     * @Template("IntractoSecretSantaBundle:Pool:created.html.twig")
     */
    public function createdAction($listUrl)
    {
        $pool = $this->getPool($listUrl);
        if (!$pool->getCreated()) {
            return $this->redirect($this->generateUrl('pool_exclude', ['listUrl' => $pool->getListurl()]));
        }

        return [
            'pool' => $pool,
        ];
    }

    /**
     * @Route("/exclude/{listUrl}", name="pool_exclude")
     * @Template("IntractoSecretSantaBundle:Pool:exclude.html.twig")
     */
    public function excludeAction(Request $request, $listUrl)
    {
        /** @var MailerService $mailerService */
        $mailerService = $this->get('intracto_secret_santa.mail');
        $pool = $this->getPool($listUrl);

        if ($pool->getCreated()) {
            $mailerService->sendPendingConfirmationMail($pool);

            return $this->redirect($this->generateUrl('pool_created', ['listUrl' => $pool->getListurl()]));
        }

        if ($pool->getEntries()->count() <= 3) {
            $pool->setCreated(true);
            $this->get('doctrine.orm.entity_manager')->persist($pool);

            $this->get('intracto_secret_santa.entry_service')->shuffleEntries($pool);

            $this->get('doctrine.orm.entity_manager')->flush();

            $mailerService->sendPendingConfirmationMail($pool);

            return $this->redirect($this->generateUrl('pool_created', ['listUrl' => $pool->getListurl()]));
        }

        $form = $this->createForm(new PoolExcludeEntryType(), $pool);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $pool->setCreated(true);
                $this->get('doctrine.orm.entity_manager')->persist($pool);

                $this->get('intracto_secret_santa.entry_service')->shuffleEntries($pool);

                $this->get('doctrine.orm.entity_manager')->flush();

                $mailerService->sendPendingConfirmationMail($pool);

                return $this->redirect($this->generateUrl('pool_created', ['listUrl' => $pool->getListurl()]));
            }
        }

        return [
            'form' => $form->createView(),
            'pool' => $pool,
        ];
    }

    /**
     * @Route("/reuse/{listUrl}", name="pool_reuse")
     * @Template("IntractoSecretSantaBundle:Pool:create.html.twig")
     */
    public function reuseAction(Request $request, $listUrl)
    {
        $pool = $this->getPool($listUrl);
        $pool = $pool->createNewPoolForReuse();

        return $this->handlePoolCreation($request, $pool);
    }

    /**
     * @Route("/delete/{listUrl}", name="pool_delete")
     * @Template("IntractoSecretSantaBundle:Pool:deleted.html.twig")
     */
    public function deleteAction(Request $request, $listUrl)
    {
        $correctCsrfToken = $this->isCsrfTokenValid(
            'delete_pool',
            $request->get('csrf_token')
        );
        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($this->get('translator')->trans('pool_valid.delete.phrase_to_type')));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('flashes.pool.not_deleted')
            );

            return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
        }

        $pool = $this->getPool($listUrl);

        $this->get('doctrine.orm.entity_manager')->remove($pool);
        $this->get('doctrine.orm.entity_manager')->flush();
    }

    /**
     * @param Request $request
     * @param Pool    $pool
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function handlePoolCreation(Request $request, Pool $pool)
    {
        $form = $this->createForm(
            PoolType::class,
            $pool,
            [
                'action' => $this->generateUrl('create_pool'),
            ]
        );

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

                $message = $this->get('translator')->trans('pool_controller.created.message', [
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
     * Retrieve pool by url.
     *
     * @param $listUrl
     *
     * @return Pool
     *
     * @throws NotFoundHttpException
     */
    private function getPool($listUrl)
    {
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if ($pool === null) {
            throw new NotFoundHttpException();
        }

        return $pool;
    }
}
