<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Entity\Participant;
use Intracto\SecretSantaBundle\Form\Type\UnsubscribeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UnsubscribeController extends Controller
{
    /**
     * @Route("/unsubscribe/{url}", name="unsubscribe_confirm")
     * @Template("IntractoSecretSantaBundle:Participant:unsubscribe.html.twig")
     * @Method("GET")
     */
    public function confirmAction($url)
    {
        /** @var Participant $participant */
        $participant = $this->get('intracto_secret_santa.repository.participant')->findOneByUrl($url);
        if ($participant === null) {
            throw new NotFoundHttpException();
        }

        $unsubscribeForm = $this->createForm(
            UnsubscribeType::class,
            null,
            [
                'action' => $this->generateUrl(
                    'unsubscribe_action',
                    ['url' => $participant->getUrl()]
                ),
            ]
        );

        return [
            'unsubscribeForm' => $unsubscribeForm->createView(),
            'participant' => $participant,
            'party' => $participant->getParty(),
        ];
    }

    /**
     * @Route("/unsubscribe/{url}", name="unsubscribe_action")
     * @Method("POST")
     */
    public function unsubscribeAction(Request $request, $url)
    {
        $unsubscribeForm = $this->createForm(UnsubscribeType::class);
        $unsubscribeForm->handleRequest($request);

        if ($unsubscribeForm->isValid()) {
            $participant = $this->get('intracto_secret_santa.repository.participant')->findOneByUrl($url);
            $allParties = $unsubscribeForm->getData()['allParties'];
            $blacklist = $unsubscribeForm->getData()['blacklist'];

            if ($blacklist) {
                $ip = $request->getClientIp();
                $this->get('intracto_secret_santa.service.unsubscribe')->blacklist($participant, $ip);
            } else {
                $this->get('intracto_secret_santa.service.unsubscribe')->unsubscribe($participant, $allParties);
            }

            $this->addFlash('success', $this->get('translator')->trans('participant_unsubscribe.feedback.success'));
        } else {
            $this->addFlash('danger', $this->get('translator')->trans('participant_unsubscribe.feedback.error'));

            return $this->redirect($this->generateUrl('unsubscribe_confirm', ['url' => $url]));
        }

        return $this->redirect($this->generateUrl('homepage'));
    }
}
