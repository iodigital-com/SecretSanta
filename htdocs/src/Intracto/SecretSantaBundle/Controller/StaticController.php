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
        $dbal = $this->getDoctrine()->getConnection();
        $pools = $dbal->fetchAll('
            SELECT count(*) created, date(sentdate) sentdate
            FROM Pool
            GROUP BY date(sentdate)
            ORDER BY date(sentdate)
        ');

        return array('pools' => $pools);
    }
}
