<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Handler\ForgotUrlFormHandler;
use App\Form\Type\ForgotLinkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForgotURLController extends AbstractController
{
    #[Route('/{_locale}/forgot-link', name: 'forgot_url', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, ForgotUrlFormHandler $handler): RedirectResponse|Response
    {
        $form = $this->createForm(ForgotLinkType::class);

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('Party/forgotLink.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
