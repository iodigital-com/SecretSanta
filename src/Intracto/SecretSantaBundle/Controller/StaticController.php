<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Model\AnalyticsOptions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StaticController extends Controller
{
    /**
     * @Route("/privacy-policy", name="privacypolicy")
     * @Template()
     */
    public function privacyPolicyAction()
    {
        return [];
    }

    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function reportAction()
    {
        $reportingService = $this->get('intracto_secret_santa.reporting');
        $pools = $reportingService->getPools();
        $options = new AnalyticsOptions();
        $options->loadFromRequest($this->getRequest());

        $data =  array(
            'pools' => $pools,
            'options' => $options,
        );

        try {
            $data['analytics'] = $reportingService->getAnalytics($options);
        } catch (\Exception $e) {
            $translator = $this->get('translator');
            $this->addFlash(
                'error',
                $translator->trans('flashes.analytics.invalid_data')
            );
        }


        return $data;
    }

    /**
     * @Route("/faq", name="faq")
     * @Template()
     */
    public function faqAction()
    {
        return [];
    }
}
