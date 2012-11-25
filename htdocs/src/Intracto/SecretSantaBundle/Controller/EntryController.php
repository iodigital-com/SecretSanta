<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Intracto\SecretSantaBundle\Entity\Entry;

class EntryController extends Controller
{
    /**
     * @Route("/entry/{url}", name="entry_view")
     * @Template()
     */
    public function indexAction($url)
    {
        $this->getEntry($url);

        // TODO: log visit on first access

        return array(
            'entry' => $this->entry,
        );
    }

    /**
     * Retrieve entry by url
     *
     * @param string $url
     *
     * @return boolean
     */
    protected function getEntry($url)
    {
        $this->entry = $this->getDoctrine()->getRepository('IntractoSecretSantaBundle:Entry')->findOneByUrl($url);

        if (!is_object($this->entry)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        return true;
    }
}
