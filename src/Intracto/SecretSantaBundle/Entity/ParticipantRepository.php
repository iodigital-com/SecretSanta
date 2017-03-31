<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ParticipantRepository extends EntityRepository
{
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
            WHERE participant.email LIKE :email
        ');
        $query->setParameter('email', $email);

        return $query->getResult();
    }
}
