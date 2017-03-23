<?php

namespace Intracto\SecretSantaBundle\Controller\Pool;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\ForgotLinkType;

class ForgotManagementURLController extends Controller
{
    /**
     * @Route("/forgot-link", name="forgot_management_url")
     * @Template("IntractoSecretSantaBundle:Pool:forgotLink.html.twig")
     */
    public function indexAction()
    {
        $form = $this->createForm(
            ForgotLinkType::class,
            null,
            [
                'action' => $this->generateUrl('resend_management_url'),
            ]
        );

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/resend-management-url", name="resend_management_url")
     * @Method("POST")
     * @Template("IntractoSecretSantaBundle:Pool:forgotLink.html.twig")
     */
    public function resendAction(Request $request)
    {
        $form = $this->createForm(
            ForgotLinkType::class,
            null,
            [
                'action' => $this->generateUrl('resend_management_url'),
            ]
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($this->get('intracto_secret_santa.mail')->sendForgotManageLinkMail($form->getData()['email'])) {
                $feedback = [
                    'type' => 'success',
                    'message' => $this->get('translator')->trans('flashes.forgot_management_url.success'),
                ];
            } else {
                $feedback = [
                    'type' => 'error',
                    'message' => $this->get('translator')->trans('flashes.forgot_management_url.error'),
                ];
            }

            $this->addFlash($feedback['type'], $feedback['message']);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
