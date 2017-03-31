<?php

namespace Intracto\SecretSantaBundle\Service;

use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\ORM\EntityManager;

class UnsubscribeService
{
    /** @var EntityManager */
    public $em;
    /** @var Router */
    public $router;

    /**
     * @param EntityManager $em
     * @param Router        $router
     */
    public function __construct(
        EntityManager $em,
        Router $router
    ) {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param Participant $participant
     * @param bool        $fromAllParties
     *
     * @return StreamedResponse
     */
    public function unsubscribe($participant, $fromAllParties)
    {
        if ($fromAllParties) {
            $participants = $this->em->getRepository('IntractoSecretSantaBundle:Participant')->findAllByEmail($participant->getEmail());
            foreach ($participants as $participant) {
                $participant->unsubscribe();
                $this->em->persist($participant);
            }
        } else {
            $participant->unsubscribe();
            $this->em->persist($participant);
        }
        $this->em->flush();
    }

    /**
     * @param Participant $participant
     *
     * @return string
     */
    public function getUnsubscribeLink(Participant $participant)
    {
        return $this->router->generate('unsubscribe_confirm', ['url' => $participant->getUrl()]);
    }
}
