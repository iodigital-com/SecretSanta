<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\MessageFormType;

class MessageController extends Controller
{
    /**
     * @Route("message/send", name="message_send")
     * @Method("POST")
     */
    public function sendAction(Request $request)
    {
        $messageForm = $this->createForm(MessageFormType::class);

        $messageForm->handleRequest($request);
        $url = $messageForm->getData()['entry'];
        if ($messageForm->isValid()) {
            $message = $messageForm->getData()['message'];
            $recipient = $messageForm->getData()['recipient'];
            $url = $messageForm->getData()['entry'];

            if ($this->get('intracto_secret_santa.mail')->sendAnonymousMessage($recipient, $message)) {
                $feedback = [
                        'type' => 'success',
                        'message' => $this->get('translator')->trans('send_message.feedback.success'),
                    ];
            } else {
                $feedback = [
                        'type' => 'danger',
                        'message' => $this->get('translator')->trans('send_message.feedback.error'),
                    ];
            }

            $this->addFlash($feedback['type'], $feedback['message']);
        } else {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans('send_message.feedback.error_in_form')
            );
        }

        return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
    }
}
