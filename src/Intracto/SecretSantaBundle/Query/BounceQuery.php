<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Driver\Connection;

class BounceQuery
{
    /** @var Connection */
    private $dbal;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    public function getBounces(): array
    {
        return $this->dbal->fetchAll('SELECT * FROM bounce');
    }

    /**
     * @param string    $email
     * @param \DateTime $date
     *
     * @return mixed
     */
    public function findBouncedParticipantId(string $email, \DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('id')
            ->from('participant')
            ->where('email = :email')
            ->andWhere('invitation_sent_date IS NOT NULL')
            ->andWhere('invitation_sent_date <= :bouncedate')
            ->andWhere('datediff(invitation_sent_date, :bouncedate) < 2')
            ->orderBy('ABS( TIMESTAMPDIFF(SECOND,:bouncedate, invitation_sent_date ) )', 'asc')
            ->setMaxResults(1)
            ->setParameter('bouncedate', $date, \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('email', $email);

        return $query->execute()->fetchColumn();
    }

    /**
     * @param int $id
     */
    public function markParticipantEmailAsBounced(int $id)
    {
        $query = $this->dbal->createQueryBuilder()
            ->update('participant')
            ->set('email_did_bounce', '1')
            ->where('id = :participantId')
            ->setParameter('participantId', $id);

        $query->execute();
    }

    /**
     * @param int $id
     */
    public function removeBounce(int $id)
    {
        $this->dbal->delete('bounce', ['id' => $id]);
    }
}
