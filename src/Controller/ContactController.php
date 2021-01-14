<?php

namespace App\Controller;

use App\Form\Handler\ContactFormHandler;
use App\Form\Type\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"GET", "POST"})
     * @Template("Static/contact.html.twig")
     */
    public function indexAction(Request $request, ContactFormHandler $handler)
    {
        $form = $this->createForm(ContactType::class);

        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }

        return ['form' => $form->createView()];
    }
}
