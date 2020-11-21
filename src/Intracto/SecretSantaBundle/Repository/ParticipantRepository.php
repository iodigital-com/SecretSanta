<?php

namespace Intracto\SecretSantaBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Intracto\SecretSantaBundle\Entity\Participant;

class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    /**
     * @param \DateTime $startDate
     *
     * @return Participant[]
     */
    public function findAfter(\DateTime $startDate)
    {
        $query = $this->_em->createQuery('
            SELECT participant
            FROM IntractoSecretSantaBundle:Participant participant
            JOIN participant.party party
            JOIN participant.assignedParticipant assignedParticipant
            WHERE party.sentdate >= :startDate
              AND assignedParticipant.wishlist IS NOT NULL
        ');
        $query->setParameter('startDate', $startDate, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * @Param String $email
     *
     * @Return Participant[]
     */
    public function findAllByEmail($email)
    {
        $query = $this->_em->createQuery('
            SELECT participant
            FROM IntractoSecretSantaBundle:Participant participant
            WHERE participant.email = :email
        ');
        $query->setParameter('email', $email);

        return $query->getResult();
    }

    /**
     * Return all the participants, which are not admin, the event is in the future or past week, and participant is already assigned to a participant.
     *
     * @Param String $email
     *
     * @Return Participant[]
     */
    public function findAllParticipantsForForgotEmail($email)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->addSelect('participant')
            ->from('IntractoSecretSantaBundle:Participant', 'participant')
            ->join('participant.party', 'party')
            ->andWhere('participant.partyAdmin = false')
            ->andWhere('participant.email = :email')
            ->andWhere('party.eventdate >= :date')
            ->andWhere('participant.url IS NOT NULL')
            ->orderBy('party.eventdate', 'ASC')
            ->setParameters([
                'email' => $email,
                'date' => new \DateTime('-1 week'),
            ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get the admin of a party by party Id.
     *
     * @param int $partyId
     *
     * @return Participant
     */
    public function findAdminByPartyId(int $partyId): Participant
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->addSelect('participant')
            ->from('IntractoSecretSantaBundle:Participant', 'participant')
            ->join('participant.party', 'party')
            ->andWhere('participant.partyAdmin = true')
            ->andWhere('party.id = :partyId')
            ->setParameters([
                'partyId' => $partyId,
            ]);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param int $limit
     *
     * @return Participant[]
     */
    public function findAllParticipantsWithoutGeoInfo(int $limit = 0): array
    {
        $query = $this->_em->createQuery('
            SELECT participant
            FROM IntractoSecretSantaBundle:Participant participant
            WHERE participant.geoCountry IS NULL
              AND (participant.ipv4 IS NOT NULL
               OR participant.ipv6 IS NOT NULL)
        ');

        if ($limit > 0) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    /**
     * Get a participant of a party that hasn't retrieved their match yet.
     *
     * @param int $partyId
     *
     * @return Participant|null
     */
    public function findOneUnseenByPartyId(int $partyId): ?Participant
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->addSelect('participant')
            ->from('IntractoSecretSantaBundle:Participant', 'participant')
            ->join('participant.party', 'party')
            ->andWhere('participant.viewdate IS NULL')
            ->andWhere('party.id = :partyId')
            ->setParameters([
                'partyId' => $partyId,
            ]);

        foreach ($qb->getQuery()->execute() as $result) {
            return $result;
        }

        return null;
    }
}
