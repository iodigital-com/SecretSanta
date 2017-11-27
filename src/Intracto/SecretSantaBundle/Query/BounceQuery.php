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

    public function getBounces()
    {
        $sql = 'SELECT * FROM bounce';
        $query = $this->dbal->query($sql);

        return $query->fetchAll();
    }

    /**
     * @param string $email
     * @Param \DateTime $date
     *
     * @return mixed
     */
    public function findBouncedParticipantId($email, $date)
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
     * @param $id
     */
    public function markParticipantEmailAsBounced($id)
    {
        $query = $this->dbal->createQueryBuilder()
            ->update('participant')
            ->set('email_did_bounce', '1')
            ->where('id = :participantId')
            ->setParameter('participantId', $id);

        $query->execute();
    }

    /**
     * @param $id
     */
    public function removeBounce($id)
    {
        $this->dbal->delete('bounce', array('id' => $id));
    }
}
