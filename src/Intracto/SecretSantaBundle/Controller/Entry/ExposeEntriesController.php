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
     * @Template("IntractoSecretSantaBundle:Entry:expose_all.html.twig")
     */
    public function indexAction($listUrl)
    {
        /** @var Pool $pool */
        $pool = $this->get('pool_repository')->findOneByListurl($listUrl);
        if (!$pool instanceof Pool) {
            throw new NotFoundHttpException();
        }

        return [
            'pool' => $pool
        ];
    }
}