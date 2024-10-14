<?php

namespace App\Controller;

use App\Form\Handler\ContactFormHandler;
use App\Form\Type\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/{_locale}/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, ContactFormHandler $handler): RedirectResponse|Response
    {
        $form = $this->createForm(ContactType::class);

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('Static/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
