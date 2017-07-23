<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intracto\SecretSantaBundle\Form\Type\PartyType;
use Intracto\SecretSantaBundle\Entity\Party;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("IntractoSecretSantaBundle:Party:create.html.twig")
     * @Method("GET")
     */
    public function indexAction()
    {
        $partyForm = $this->createForm(PartyType::class, new Party(), [
            'action' => $this->generateUrl('create_party'),
        ]);

        return [
            'form' => $partyForm->createView(),
        ];
    }
}
