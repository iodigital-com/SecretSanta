<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @DI\Service("intracto_secret_santa.entry_service")
 */
class EntryService
{
    /**
     * @DI\Inject("mailer")
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var EntityManager
     */
    public $em;

    /**
     * @DI\Inject("intracto_secret_santa.entry_shuffler")
     * @var EntryShuffler $entryShuffler
     */
    public $entryShuffler;

    /**
     * @DI\Inject("templating")
     * @var EngineInterface
     */
    public $templating;

    /**
     * @DI\Inject("%admin_email%")
     */
    public $adminEmail;

    /**
     * @DI\Inject("translator")
     * @var TranslatorInterface;
     */
    public $translator;

    /**
     * @DI\Inject("%admin_email%")
     * @var string $secretSantaEmail
     */
    public $secretSantaEmail;

    /**
     * Shuffles all entries for pool and save result to each entry
     *
     * @param Pool $pool
     *
     * @return boolean
     */
    public function shuffleEntries(Pool $pool)
    {
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
     * Sends out all mails for a Pool
     *
     * @param Pool $pool
     */
    public function sendSecretSantaMailsForPool(Pool $pool)
    {
        $pool->setSentdate(new \DateTime("now"));
        $this->em->flush($pool);

        foreach ($pool->getEntries() as $entry) {
            $this->sendSecretSantaMailForEntry($entry);
        }
    }

    /**
     * Sends out mail for a Entry
     *
     * @param Entry $entry
     */
    public function sendSecretSantaMailForEntry(Entry $entry)
    {
        $this->translator->setLocale($entry->getPool()->getLocale());

        $message = $entry->getPool()->getMessage();
        $message = str_replace('(NAME)', $entry->getName(), $message);
        $message = str_replace('(ADMINISTRATOR)', $entry->getPool()->getOwnerName(), $message);
        $txtBody = $this->templating->render(
            'IntractoSecretSantaBundle:Emails:secretsanta.txt.twig',
            array('message' => $message, 'entry' => $entry)
        );
        $htmlBody = $this->templating->render(
            'IntractoSecretSantaBundle:Emails:secretsanta.html.twig',
            array('message' => $message, 'entry' => $entry)
        );

        $mail = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails.secretsanta.subject'))
            ->setFrom($this->adminEmail, $entry->getPool()->getOwnerName())
            ->setTo($entry->getEmail(), $entry->getName())
            ->setBody($txtBody)
            ->addPart($htmlBody, 'text/html');
        $this->mailer->send($mail);
    }

    /**
     * @param Pool $pool
     * @return void
     */
    public function sendPoolMatchesToAdmin(Pool $pool)
    {
        $this->translator->setLocale($pool->getLocale());
        $this->mailer->send(\Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails.admin_matches.subject'))
            ->setFrom($this->secretSantaEmail, $this->translator->trans('emails.sender'))
            ->setTo($this->adminEmail, $pool->getOwnerName())
            ->setBody($this->templating->render("IntractoSecretSantaBundle:Emails:admin_matches.html.twig", array('pool' => $pool)), 'text/html')
            ->addPart($this->templating->render("IntractoSecretSantaBundle:Emails:admin_matches.txt.twig", array('pool' => $pool)), 'text/plain')
        );
    }

    /**
     * @return \Doctrine\ORM\Internal\Hydration\IterableResult
     */
    public function getAllUniqueEmailsIterator()
    {
        $repo = $this->em->getRepository('IntractoSecretSantaBundle:Entry');

        $queryBuilder = $repo->createQueryBuilder('e');
        $queryBuilder->select('e.email, e.name')
            ->groupBy('e.email');

        return $queryBuilder->getQuery()->iterate();
    }
}
