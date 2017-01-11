<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intracto\SecretSantaBundle\Form\PoolType;
use Intracto\SecretSantaBundle\Entity\Pool;

class StartpageController extends Controller
{
    /**
     * @Route("/", name="startpage")
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

        return $this->render(
            'IntractoSecretSantaBundle:Pool:create.html.twig',
            [
                'form' => $poolForm->createView(),
            ]
        );
    }
}
