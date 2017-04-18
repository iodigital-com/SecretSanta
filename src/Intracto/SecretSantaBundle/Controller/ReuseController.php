<?php

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
     * @Method("GET")
     */
    public function showRequestAction()
    {
        $form = $this->createForm(RequestReuseUrlType::class, null, [
            'action' => $this->generateUrl('send_reuse_url'),
        ]);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/reuse", name="send_reuse_url")
     * @Template("IntractoSecretSantaBundle:Party:getReuseUrl.html.twig")
     * @Method("POST")
     */
    public function sendReuseUrlAction(Request $request)
    {
        $form = $this->createForm(RequestReuseUrlType::class, null);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $reuseLinksMailSent = $this->get('intracto_secret_santa.mailer')->sendReuseLinksMail($form->getData()['email']);
            if ($reuseLinksMailSent) {
                $feedback = [
                    'type' => 'success',
                    'message' => $this->get('translator')->trans('flashes.reuse.success'),
                ];
            } else {
                $feedback = [
                    'type' => 'danger',
                    'message' => $this->get('translator')->trans('flashes.reuse.error'),
                ];
            }

            $this->addFlash($feedback['type'], $feedback['message']);
        }

        return $this->redirect($this->generateUrl('request_reuse_url'));
    }
}
