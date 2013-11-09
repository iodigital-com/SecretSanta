<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Form\WishlistType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class EntryController extends Controller
{
    /**
     * @Route("/entry/{url}", name="entry_view")
     * @Template()
     */
    public function indexAction(Request $request, $url)
    {
        $em = $this->getDoctrine()->getManager();
        $this->getEntry($url);

        $wishlist_updated = false;

        $form = $this->createForm(new WishlistType(), $this->entry);

        // Log visit on first access
        if ($this->entry->getViewdate() === null) {
            $this->entry->setViewdate(new \DateTime());
            $em->flush($this->entry);
        }

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $em->flush($this->entry);
                $wishlist_updated = true;
            }
        }

        return array(
            'entry' => $this->entry,
            'form' => $form->createView(),
            'wishlist_updated' => $wishlist_updated,

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
