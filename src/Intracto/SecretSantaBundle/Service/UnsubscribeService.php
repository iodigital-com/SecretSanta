<?php

namespace Intracto\SecretSantaBundle\Service;

use Intracto\SecretSantaBundle\Entity\BlacklistEmail;
use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
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
     *
     * Creates a link that can be used in the List-Unsubscribe header
     */
    public function getUnsubscribeLink(Participant $participant)
    {
        return '<'.$this->router->generate('unsubscribe_confirm', ['url' => $participant->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL).'>';
    }

    /**
     * @param Participant $participant
     * @param Sting       $ip
     */
    public function blacklist(Participant $participant, $ip)
    {
        // Unsubscribe participant from emails, with flag true for all parties.
        $this->unsubscribe($participant, true);
        $blacklist = new BlacklistEmail($participant->getEmail(), $ip, new \DateTime());
        $this->em->persist($blacklist);
        $this->em->flush();
    }

    public function isBlacklisted(Participant $participant)
    {
        $repository = $this->em->getRepository('IntractoSecretSantaBundle:BlacklistEmail');
        $results = $repository->createQueryBuilder('b')
            ->where('b.email = :email')
            ->setParameter('email', $participant->getEmail())
            ->getQuery()
            ->getResult();
        if (count($results) > 0) {
            return true;
        }

        return false;
    }
}
