<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Form\Type\UnsubscribeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UnsubscribeController extends Controller
{
    /**
     * @Route("/unsubscribe/{url}", name="unsubscribe_confirm")
     * @Template("IntractoSecretSantaBundle:Participant:unsubscribe.html.twig")
     * @Method({"GET", "POST"})
     */
    public function confirmAction(Request $request, Participant $participant)
    {
        $form = $this->createForm(UnsubscribeType::class);
        $handler = $this->get('intracto_secret_santa.form_handler.unsubcribe');

        if ($handler->handle($form, $request, $participant)) {
            return $this->redirectToRoute('homepage');
        }

        return [
            'unsubscribeForm' => $form->createView(),
            'participant' => $participant,
            'party' => $participant->getParty(),
        ];
    }
}
