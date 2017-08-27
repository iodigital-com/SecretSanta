<?php
declare(strict_types=1);

namespace Intracto\SecretSantaBundle\Controller\Participant;

use Intracto\SecretSantaBundle\Entity\ParticipantRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DumpParticipantsController extends AbstractController
{
    /**
     * @Route("/dump-participants", name="dump_participants")
     * @Template("IntractoSecretSantaBundle:Participant:dumpParticipants.html.twig")
     * @Method("GET")
     */
    public function dumpAction(ParticipantRepository $repository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADWORDS');

        $startCrawling = new \DateTime();
        $startCrawling->sub(new \DateInterval('P4M'));

        return [
            'participants' => $repository->findAfter($startCrawling),
        ];
    }
}
