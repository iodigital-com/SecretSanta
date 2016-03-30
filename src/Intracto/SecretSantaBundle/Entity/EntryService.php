<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @DI\Service("intracto_secret_santa.entry_service")
 */
class EntryService
{
    /**
     * @DI\Inject("mailer")
     *
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     *
     * @var EntityManager
     */
    public $em;

    /**
     * @DI\Inject("intracto_secret_santa.entry_shuffler")
     *
     * @var EntryShuffler $entryShuffler
     */
    public $entryShuffler;

    /**
     * @DI\Inject("templating")
     *
     * @var EngineInterface
     */
    public $templating;

    /**
     * @DI\Inject("%admin_email%")
     */
    public $adminEmail;

    /**
     * @DI\Inject("translator")
     *
     * @var TranslatorInterface;
     */
    public $translator;

    /**
     * Shuffles all entries for pool and save result to each entry
     *
     * @param Pool $pool
     *
     * @return bool
     */
    public function shuffleEntries(Pool $pool)
    {
        //Validator should already have shuffled it.
        if (!$shuffled = $this->entryShuffler->shuffleEntries($pool)) {
            return false;
        }

        foreach ($pool->getEntries() as $key => $entry) {
            $match = $shuffled[$key];
            $entry->setEntry($match)
                ->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

            $this->em->persist($entry);
        }

        $this->em->flush();
    }

    /**
     * @return array
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAllUniqueEmails()
    {
        $query = '  SELECT name, email, MAX( admin ) AS admin
                    FROM (
                        SELECT e.email, e.name, IF( MIN( e2.id ) = e.id, 1, 0 ) AS admin
                        FROM Entry e
                        LEFT JOIN Entry e2 ON e.poolId = e2.poolId
                        GROUP BY e.id
                    )x
                    GROUP BY email';

        $connection = $this->em->getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
    }
}
