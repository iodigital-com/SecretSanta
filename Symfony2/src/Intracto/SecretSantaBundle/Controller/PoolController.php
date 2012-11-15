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

        // Every pool needs at least 3 members: 1 owner + 2 entries
        $entry1 = new Entry();
        $entry2 = new Entry();
        $pool->addEntry($entry1);
        $pool->addEntry($entry2);
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
}
