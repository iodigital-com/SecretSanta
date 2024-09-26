<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Handler\ReuseFormHandler;
use App\Form\Type\RequestReuseUrlType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReuseController extends AbstractController
{
    #[Route('/{_locale}/reuse', name: 'request_reuse_url', methods: ['GET', 'POST'])]
    public function showRequestAction(Request $request, ReuseFormHandler $handler): RedirectResponse|Response
    {
        $form = $this->createForm(RequestReuseUrlType::class);

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('Party/getReuseUrl.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
