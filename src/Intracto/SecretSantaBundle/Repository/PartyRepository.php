<?php

namespace Intracto\SecretSantaBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Intracto\SecretSantaBundle\Entity\Party;

class PartyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Party::class);
    }

    public function findAllAdminParties($email)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->addSelect('party.listurl')
            ->addSelect('party.eventdate')
            ->addSelect('party.locale')
            ->addSelect('party.location')
            ->from('IntractoSecretSantaBundle:Party', 'party')
            ->join('party.participants', 'participants')
            ->andWhere('participants.partyAdmin = true')
            ->andWhere('participants.email = :email')
            ->andWhere('party.eventdate >= :date')
            ->orderBy('party.eventdate', 'ASC')
            ->setParameters([
                'email' => $email,
                'date' => new \DateTime('-1 week'),
            ]);

        return $qb->getQuery()->getResult();
    }

    public function findPartiesToReuse($email)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->addSelect('party.eventdate')
            ->addSelect('party.listurl')
            ->addSelect('party.locale')
            ->addSelect('party.location')
            ->from('IntractoSecretSantaBundle:Party', 'party')
            ->join('party.participants', 'participants')
            ->andWhere('participants.partyAdmin = true')
            ->andWhere('participants.email = :email')
            ->andWhere('party.eventdate >= :date')
            ->setParameters([
                'email' => $email,
                'date' => new \DateTime('-2 year'),
            ]);

        return $qb->getQuery()->getResult();
    }
}
