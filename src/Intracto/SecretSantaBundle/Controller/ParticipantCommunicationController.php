<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Intracto\SecretSantaBundle\Form\Type\AnonymousMessageFormType;

class ParticipantCommunicationController extends Controller
{
    /**
     * @Route("/participant-communication/send-message", name="participant_communication_send_message")
     * @Method("POST")
     */
    public function sendMessageAction(Request $request)
    {
        $form = $this->createForm(AnonymousMessageFormType::class);

        $this->get('intract_secret_santa.form_handler.send_message')->handle($form, $request);

        return $this->redirect($this->generateUrl('participant_view', ['url' => $form->getData()['participant']]));
    }
}
