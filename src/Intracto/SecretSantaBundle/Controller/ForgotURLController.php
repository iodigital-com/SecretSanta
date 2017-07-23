<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\ForgotLinkType;

class ForgotURLController extends Controller
{
    /**
     * @Route("/forgot-link", name="forgot_url")
     * @Template("IntractoSecretSantaBundle:Party:forgotLink.html.twig")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ForgotLinkType::class);

        $handler = $this->get('intracto_secret_santa.form_handler.forgot_url');

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
