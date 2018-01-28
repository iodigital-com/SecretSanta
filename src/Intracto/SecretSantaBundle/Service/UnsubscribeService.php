<?php

namespace Intracto\SecretSantaBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Intracto\SecretSantaBundle\Entity\BlacklistEmail;
use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

class UnsubscribeService
{
    /** @var EntityManager */
    public $em;

    /** @var Router */
    public $router;

    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param Participant $participant
     * @param bool        $fromAllParties
     */
    public function unsubscribe(Participant $participant, bool $fromAllParties)
    {
        if ($fromAllParties) {
            /** @var Participant[] $participants */
            $participants = $this->em->getRepository('IntractoSecretSantaBundle:Participant')->findAllByEmail($participant->getEmail());
            foreach ($participants as $p) {
                $p->unsubscribe();
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
     * @param string      $ip
     */
    public function blacklist(Participant $participant, string $ip)
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

        return count($results) > 0;
    }
}
