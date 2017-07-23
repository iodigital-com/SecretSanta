<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Form\Handler\ReuseFormHandler;
use Intracto\SecretSantaBundle\Form\Type\RequestReuseUrlType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReuseController extends AbstractController
{
    /**
     * @Route("/reuse", name="request_reuse_url")
     * @Template("IntractoSecretSantaBundle:Party:getReuseUrl.html.twig")
     * @Method({"GET", "POST"})
     */
    public function showRequestAction(Request $request, ReuseFormHandler $handler)
    {
        $form = $this->createForm(RequestReuseUrlType::class);

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
