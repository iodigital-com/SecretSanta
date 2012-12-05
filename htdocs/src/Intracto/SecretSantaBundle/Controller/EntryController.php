<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EntryController extends Controller
{
    /**
     * @Route("/entry/{url}", name="entry_view")
     * @Template()
     */
    public function indexAction($url)
    {
        $em = $this->getDoctrine()->getManager();
        $this->getEntry($url);

        // Log visit on first access
        if ($this->entry->getViewdate() === null) {
            $this->entry->setViewdate(new \DateTime());
            $em->flush($this->entry);
        }

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
