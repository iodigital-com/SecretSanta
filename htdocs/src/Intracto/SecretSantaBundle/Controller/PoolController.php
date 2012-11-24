<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Form\PoolType;
use Intracto\SecretSantaBundle\Entity\Entry;

class PoolController extends Controller
{
    /**
     * @Route("/", name="pool_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $pool = new Pool();


        $form = $this->createForm(new PoolType(), $pool);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                foreach ($pool->getEntries() as $entry) {
                    $entry->setPool($pool);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($pool);
                $em->flush();

                return new Response(
                    $this->renderView('IntractoSecretSantaBundle:Pool:created.html.twig', array('pool' => $pool))
                );
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
    /**
     * @Route("/manage/{listurl}", name="pool_manage")
     * @Template()
     */
    public function manageAction($listurl)
    {
        $this->getPool($listurl);

        if($this->pool->getSentdate() === NULL){
            
            // Get repository
            $em = $this->getDoctrine()->getEntityManager();
            $repository = $em->getRepository('IntractoSecretSantaBundle:Entry');

            // Shuffle
            $repository->shuffleEntries($this->pool);

            // Send mails
            $repository->sendSecretSantaMailsForPool($this->pool);
        }

        return array('pool' => $this->pool);
    }

    /*
    * Retrieve pool by url
    * @param string $url
    * @return boolean
    */

    protected function getPool($listurl)
    {
        $this->pool = $this->getDoctrine()->getRepository('IntractoSecretSantaBundle:Pool')->findOneByListurl($listurl);
        if (!is_object($this->pool)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        return true;
    }
}
