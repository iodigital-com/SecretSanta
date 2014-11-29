<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StaticController extends Controller
{
    /**
     * @Route("/privacy-policy", name="privacypolicy")
     * @Template()
     */
    public function privacypolicyAction()
    {
        return array();
    }

    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function reportAction()
    {
        return array(
            'pools' => $this->getPools(),
            'gaCountries' => $this->getGaCountries(),
        );
    }

    private function getPools()
    {
        if ($this->get('cache')->contains('report_pools')) {
            return unserialize($this->get('cache')->fetch('report_pools'));
        }

        $dbal = $this->getDoctrine()->getConnection();

        $pools = $dbal->fetchAll('
            SELECT count(*) created, date(sentdate) sentdate
            FROM Pool
            GROUP BY date(sentdate)
            ORDER BY date(sentdate)
        ');

        $this->get('cache')->save('report_pools', serialize($pools), 3600);

        return $pools;
    }

    private function getGaCountries()
    {
        if ($this->get('cache')->contains('ga_countries')) {
            return unserialize($this->get('cache')->fetch('ga_countries'));
        }

        require __DIR__.'/../../../../lib/gapi.class.php';

        $ga = new \gapi(
            $this->container->getParameter('ga_email'),
            $this->container->getParameter('ga_password')
        );

        $ga->requestReportData(
            $this->container->getParameter('ga_profile_id'),
            array('country'),
            array('visits'),
            '-visits',
            null,
            date('Y-m-d', strtotime('-1 month')),
            date('Y-m-d'),
            1,
            100
        );

        $results = array(
            'totals' => $ga->getVisits(),
        );
        foreach ($ga->getResults() as $country) {
            $results['countries'][(string) $country] = $country->getVisits();
        }

        $this->get('cache')->save('ga_countries', serialize($results), 3600);

        return $results;
    }
}
