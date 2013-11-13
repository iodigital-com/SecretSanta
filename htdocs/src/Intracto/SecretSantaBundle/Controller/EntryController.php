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
                $this->get('session')->getFlashBag()->add(
                    'success',
                    '<h4>Wishlist updated</h4>We\'ve sent out our gnomes to notify your Secret Santa about your wishes!'
                );
            }
        }

        $secret_santa = $this->entry->getEntry();

        return array(
            'entry' => $this->entry,
            'form' => $form->createView(),
            'secret_santa' => $secret_santa,
        );
    }

    /**
     * Retrieve entry by url
     *
     * @param string $url
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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
