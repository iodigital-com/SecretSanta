<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intracto\SecretSantaBundle\Form\Type\PoolType;
use Intracto\SecretSantaBundle\Entity\Pool;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("IntractoSecretSantaBundle:Pool:create.html.twig")
     */
    public function indexAction()
    {
        $poolForm = $this->createForm(
            PoolType::class,
            new Pool(),
            [
                'action' => $this->generateUrl('create_pool'),
            ]
        );

        return [
            'form' => $poolForm->createView(),
        ];
    }
}
