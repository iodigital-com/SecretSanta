<?php

namespace Intracto\SecretSantaBundle\Controller\Wishlist;

use Intracto\SecretSantaBundle\Entity\Pool;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExposeWishlistsController extends Controller
{
    /**
     * @Route("/wishlists/expose/{listUrl}", name="pool_expose_wishlists")
     * @Template("IntractoSecretSantaBundle:Wishlist:expose.html.twig")
     */
    public function exposeWishlistsAction(Request $request, $listUrl)
    {

        /** @var Pool $pool */
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if (!is_object($pool)) {
            throw new NotFoundHttpException();
        }

        $pool->exposeWishlists();

        $this->get('doctrine.orm.entity_manager')->flush();

        return [
            'pool' => $pool,
        ];
    }
}