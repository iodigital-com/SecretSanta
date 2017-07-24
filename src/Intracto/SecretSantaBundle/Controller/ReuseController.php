<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Form\Type\RequestReuseUrlType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReuseController extends Controller
{
    /**
     * @Route("/reuse", name="request_reuse_url")
     * @Template("IntractoSecretSantaBundle:Party:getReuseUrl.html.twig")
     * @Method({"GET", "POST"})
     */
    public function showRequestAction(Request $request)
    {
        $form = $this->createForm(RequestReuseUrlType::class);

        $handler = $this->get('intracto_secret_santa.form_handler.reuse');

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
