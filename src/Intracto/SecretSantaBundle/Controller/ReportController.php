<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Entity\Entry;

class ReportController extends Controller
{
    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function reportAction(){

        $em = $this->getDoctrine()->getManager();
        $poolss = $em->getRepository("IntractoSecretSantaBundle:Pool")->findAll();
        $entries = $em->getRepository("IntractoSecretSantaBundle:Entry")->findAll();

        $reportingServices = $this->get('intracto_secret_santa.report.reporting');
        $pools = $reportingServices->getPools();

        $data = array(
            "pools" => $pools,
            "entries" => $entries
        );

        return $data;
    }

}
