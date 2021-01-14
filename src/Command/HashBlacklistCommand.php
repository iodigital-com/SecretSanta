<?php

namespace App\Command;

use App\Entity\BlacklistEmail;
use App\Service\HashService;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HashBlacklistCommand extends Command
{
    private EntityManagerInterface $em;
    private HashService $hashService;

    public function __construct(
        EntityManagerInterface $em,
        HashService $hashService
    ) {
        $this->em = $em;
        $this->hashService = $hashService;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('intracto:hash-black-list')
            ->setDescription('Hash black listed mail addresses.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blacklistedMails = $this->em->getRepository(BlacklistEmail::class)->findAll();

        /** @var BlacklistEmail $blacklistedMail */
        foreach ($blacklistedMails as $blacklistedMail) {
            $email = $blacklistedMail->getEmail();
            if ($this->hashService->isAlreadyHashed($email)) {
                continue;
            }
            $hashedEmail = $this->hashService->hashEmail($email);
            $blacklistedMail->setEmail($hashedEmail);
            $this->em->flush();
        }

        return 0;
    }
}
