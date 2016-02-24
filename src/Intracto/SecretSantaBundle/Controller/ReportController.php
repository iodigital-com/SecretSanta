<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{
    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function reportAction(Request $request)
    {
        $reportingServices = $this->get('intracto_secret_santa.report.reporting');

        $pools = $reportingServices->getPools($request);
        $entries = $reportingServices->getEntries($request);
        $wishlists = $reportingServices->getFinishedWishlists($request);
        $years = $reportingServices->getYears();

        if (count($pools) != 0) {
            $entry_average = number_format(implode($entries[0]) / implode($pools[0]), 2);
        } else {
            $entry_average = number_format(0);
        }

        if (count($entries) != 0) {
            $wishlist_average = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);
        } else {
            $wishlist_average = number_format(0);
        }
        if (isset ($_GET['year'])) {
            $current_year = $_GET['year'];
        } else {
            $current_year = 'all';
        }

        $data = array(
            "pools" => $pools,
            "entries" => $entries,
            "entry_average" => $entry_average,
            "wishlist_average" => $wishlist_average,
            "years" => $years,
            "current_year" => $current_year
        );

        return $data;
    }
}