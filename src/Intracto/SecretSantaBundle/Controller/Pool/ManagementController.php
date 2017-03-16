<?php

namespace Intracto\SecretSantaBundle\Controller\Pool;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Entity\Entry;
use Intracto\SecretSantaBundle\Form\Type\AddEntryType;
use Intracto\SecretSantaBundle\Form\Type\UpdatePoolDetailsType;

class ManagementController extends Controller
{
    /**
     * @Route("/manage/{listUrl}", name="pool_manage")
     * @Template("IntractoSecretSantaBundle:Pool/manage:valid.html.twig")
     */
    public function validAction(Request $request, $listUrl)
    {
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if ($pool === null) {
            throw new NotFoundHttpException();
        }

        if (!$pool->getCreated()) {
            return $this->redirect($this->generateUrl('pool_exclude', ['listUrl' => $pool->getListurl()]));
        }

        if ($pool->getSentdate() === null) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('flashes.management.email_validated')
            );

            $this->get('intracto_secret_santa.mail')->sendSecretSantaMailsForPool($pool);
        }

        $newEntry = new Entry();
        $updatePool = $pool;

        $addEntryForm = $this->createForm(AddEntryType::class, $newEntry);
        $updatePoolDetailsForm = $this->createForm(UpdatePoolDetailsType::class, $updatePool);

        if ($pool->getEventdate() < new \DateTime('-2 years')) {
            return $this->render('IntractoSecretSantaBundle:Pool/manage:expired.html.twig', [
                'pool' => $pool,
                'delete_pool_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_pool'),
            ]);
        }

        if ($request->getMethod('POST')) {
            $addEntryForm->handleRequest($request);
            $updatePoolDetailsForm->handleRequest($request);

            if ($addEntryForm->isSubmitted()) {
                if ($addEntryForm->isValid()) {
                    $newEntry->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
                    $newEntry->setPool($pool);

                    $this->get('doctrine.orm.entity_manager')->persist($newEntry);
                    $this->get('doctrine.orm.entity_manager')->flush($newEntry);

                    $adminId = $this->get('intracto_secret_santa.entry')->findAdminIdByPoolId($pool->getId());
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
                        $this->get('translator')->trans('flashes.management.add_participant.success')
                    );

                    return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'danger',
                        $this->get('translator')->trans('flashes.management.add_participant.danger')
                    );
                }
            }

            if ($updatePoolDetailsForm->isSubmitted()) {
                if ($updatePoolDetailsForm->isValid()) {
                    $time_now = new \DateTime();

                    $updatePool->setDetailsUpdatedTime($time_now);

                    $this->get('doctrine.orm.entity_manager')->persist($updatePool);
                    $this->get('doctrine.orm.entity_manager')->flush();

                    $this->get('intracto_secret_santa.mail')->sendPoolUpdatedMailsForPool($updatePool);

                    $this->get('session')->getFlashBag()->add(
                        'success',
                        $this->get('translator')->trans('flashes.management.updated_party.success')
                    );

                    return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'danger',
                        $this->get('translator')->trans('flashes.management.updated_party.danger')
                    );
                }
            }
        }

        return [
            'addEntryForm' => $addEntryForm->createView(),
            'updatePoolDetailsForm' => $updatePoolDetailsForm->createView(),
            'pool' => $pool,
            'delete_pool_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_pool'),
            'delete_participant_csrf_token' => $this->get('security.csrf.token_manager')->getToken('delete_participant'),
        ];
    }
}
