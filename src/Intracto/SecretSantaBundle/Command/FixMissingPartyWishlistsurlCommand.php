<?php

namespace Intracto\SecretSantaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixMissingPartyWishlistsurlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('intracto:fix:missing_party_wishlists')
            ->setDescription('TEMPORARY');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Doctrine\DBAL\Driver\Connection $dbal */
        $conn = $this->getContainer()->get('doctrine.dbal.default_connection');
        $parties = $conn->query('select id from party where wishlists_url = ""');
        $stmt = $conn->prepare('update party set wishlists_url = :wishlists_url where id = :id');
        foreach ($parties as $party) {
            $stmt->bindValue('id', $party['id']);
            $stmt->bindValue('wishlists_url', base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
            $stmt->execute();
            echo '.';
        }
        echo "\n\nDONE\n";
    }
}
