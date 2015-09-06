<?php

namespace Intracto\SecretSantaBundle\Tests;

use Intracto\SecretSantaBundle\Entity\Entry;
use Intracto\SecretSantaBundle\Entity\EntryShuffler;
use Intracto\SecretSantaBundle\Entity\Pool;

class EntryShufflerTest extends \PHPUnit_Framework_TestCase
{
    public function testEntryShuffler()
    {
        $pool = new Pool();
        foreach ($pool->getEntries() as $defaultEntry) {
            $pool->removeEntry($defaultEntry);
        }

        $entry1 = new Entry();
        $entry1->setName('Entry 1');
        $pool->addEntrie($entry1);

        $entry2 = new Entry();
        $entry2->setName('Entry 2');
        $pool->addEntrie($entry2);

        $entry3 = new Entry();
        $entry3->setName('Entry 3');
        $pool->addEntrie($entry3);

        $entry4 = new Entry();
        $entry4->setName('Entry 4');
        $pool->addEntrie($entry4);

        $entry1->addExcludedEntry($entry2);
        $entry2->addExcludedEntry($entry3);
        $entry4->addExcludedEntry($entry1);
        $entry4->addExcludedEntry($entry2);

        $entryShuffler = new EntryShuffler();
        for ($i = 0; $i < 1000; $i++) {
            $shuffeledEntries = $entryShuffler->shuffleEntries($pool);

            $key = 0;
            foreach ($pool->getEntries() as $entry) {
                //check if we did not match excluded entry
                $this->assertNotContains($shuffeledEntries[$key], $entry->getExcludedEntries());
                //check if we have an entry matched
                $this->assertNotNull($shuffeledEntries[$key]);
                $key++;
            }
        }
    }
}
