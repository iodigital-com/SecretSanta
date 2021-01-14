<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Handler\ForgotUrlFormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\ForgotLinkType;
use Symfony\Component\Routing\Annotation\Route;

class ForgotURLController extends AbstractController
{
    /**
     * @Route("/forgot-link", name="forgot_url", methods={"GET", "POST"})
     * @Template("Party/forgotLink.html.twig")
     */
    public function indexAction(Request $request, ForgotUrlFormHandler $handler)
    {
        $form = $this->createForm(ForgotLinkType::class);

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
