<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intracto\SecretSantaBundle\Form\Type\PartyType;
use Intracto\SecretSantaBundle\Entity\Party;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("IntractoSecretSantaBundle:Pool:create.html.twig")
     */
    public function indexAction()
    {
        $partyForm = $this->createForm(
            PartyType::class,
            new Party(),
            [
                'action' => $this->generateUrl('create_pool'),
            ]
        );

        return [
            'form' => $partyForm->createView(),
        ];
    }
}
