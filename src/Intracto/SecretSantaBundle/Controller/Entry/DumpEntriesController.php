<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DumpEntriesController extends Controller
{
    /**
     * @Route("/dump-entries", name="dump_entries")
     * @Template("IntractoSecretSantaBundle:Entry:dumpEntries.html.twig")
     */
    public function dumpAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADWORDS');

        $startCrawling = new \DateTime();
        $startCrawling->sub(new \DateInterval('P4M'));

        return [
            'entries' => $this->get('entry_repository')->findAfter($startCrawling),
        ];
    }
}
