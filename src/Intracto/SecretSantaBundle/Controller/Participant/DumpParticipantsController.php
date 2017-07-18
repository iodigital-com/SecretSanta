<?php

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DumpParticipantsController extends Controller
{
    /**
     * @Route("/dump-participants", name="dump_participants")
     * @Template("IntractoSecretSantaBundle:Participant:dumpParticipants.html.twig")
     * @Method("GET")
     */
    public function dumpAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADWORDS');

        $startCrawling = new \DateTime();
        $startCrawling->sub(new \DateInterval('P4M'));

        return [
            'participants' => $this->get('intracto_secret_santa.repository.participant')->findAfter($startCrawling),
        ];
    }
}
