<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Entity\Pool;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WishlistController extends Controller
{
    /**
     * @Route("/wishlists/show/{listUrl}", name="wishlist_show_all")
     * @Template("IntractoSecretSantaBundle:Wishlist:show_all.html.twig")
     */
    public function showAllAction($listUrl)
    {
        /** @var Pool $pool */
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);

        if (!is_object($pool)) {
            throw new NotFoundHttpException();
        }

        return [
            'pool' => $pool,
        ];
    }
}