<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Intracto\SecretSantaBundle\Entity\Pool;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExposeEntriesController extends Controller
{
    /**
     * @Route("/entries/expose/{listUrl}", name="expose_entries")
     * @Template("IntractoSecretSantaBundle:Entry:exposeAll.html.twig")
     */
    public function indexAction($listUrl)
    {
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if ($pool === null) {
            throw new NotFoundHttpException();
        }

        return [
            'pool' => $pool
        ];
    }
}