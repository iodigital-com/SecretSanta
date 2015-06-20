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
        $reportingService = $this->get('intracto_secret_santa.reporting');
        return array(
            'pools' => $reportingService->getPools(),
            'gaCountries' => $reportingService->getGaCountries(),
        );
    }
}
