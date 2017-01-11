<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExposeEntriesController extends Controller
{
    /**
     * @Route("/expose/{listUrl}", name="expose_entries")
     * @Template()
     */
    public function indexAction(Request $request, $listUrl)
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
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if (!is_object($pool)) {
            throw new NotFoundHttpException();
        }
        $pool->expose();

        /* Save db changes */
        $this->get('doctrine.orm.entity_manager')->flush();

        /* Mail pool owner the pool matches */
        $this->get('intracto_secret_santa.mail')->sendPoolMatchesToAdmin($pool);

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }
}