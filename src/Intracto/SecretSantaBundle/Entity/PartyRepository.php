<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PartyRepository extends EntityRepository
{
    public function findAllAdminParties($email)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->addSelect('party.listurl')
            ->addSelect('party.eventdate')
            ->addSelect('party.locale')
            ->from('IntractoSecretSantaBundle:Party', 'party')
            ->join('party.participants', 'participants')
            ->andWhere('participants.partyAdmin = true')
            ->andWhere('participants.email = :email')
            ->andWhere('party.eventdate >= :date')
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
