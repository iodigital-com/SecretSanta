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
            $entry_average = number_format(count($entries) / count($pools), 2);
        } else {
            $entry_average = number_format(0);
        }

        if (count($entries) != 0) {
            $wishlist_average = number_format((count($wishlists) / count($entries)) * 100, 2);
        } else {
            $wishlist_average = number_format(0);
        }

        $data = array(
            "pools" => $pools,
            "entries" => $entries,
            "entry_average" => $entry_average,
            "wishlist_average" => $wishlist_average,
            "years" => $years
        );

        return $data;
    }
}
