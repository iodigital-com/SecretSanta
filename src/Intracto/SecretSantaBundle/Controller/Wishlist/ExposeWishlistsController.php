<?php

namespace Intracto\SecretSantaBundle\Controller\Wishlist;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExposeEntriesController extends Controller
{
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

        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if (!is_object($pool)) {
            throw new NotFoundHttpException();
        }
        $pool->exposeWishlists();

        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('intracto_secret_santa.mail')->sendAllWishlistsToAdmin($pool);

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }
}