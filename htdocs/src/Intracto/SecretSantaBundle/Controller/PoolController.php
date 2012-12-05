<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Form\PoolType;
use Intracto\SecretSantaBundle\Entity\Entry;

class PoolController extends Controller
{
    /**
     * @DI\Inject("%admin_email%")
     */
    public $adminEmail;

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

                // Send pending confirmation mail
                $message = \Swift_Message::newInstance()
                    ->setSubject('Secret Santa Confirmation')
                    ->setFrom($this->adminEmail)
                    ->setTo($pool->getOwnerEmail())
                    ->setBody(
                        $this->renderView(
                            'IntractoSecretSantaBundle:Emails:pendingconfirmation.txt.twig',
                            array('pool' => $pool)
                        )
                    )
                    ->addPart(
                        $this->renderView(
                            'IntractoSecretSantaBundle:Emails:pendingconfirmation.html.twig',
                            array('pool' => $pool)
                        ),
                        'text/html'
                    )
                ;
                $this->get('mailer')->send($message);

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

        if ($this->pool->getSentdate() === null) {
            $first_time = true;
            $entryService = $this->get('intracto_secret_santa.entry_service');

            // Shuffle
            $entryService->shuffleEntries($this->pool);

            // Send mails
            $entryService->sendSecretSantaMailsForPool($this->pool);
        }else{
            $first_time = false;
        }

        return array('pool' => $this->pool, 'first_time' => $first_time);
    }

    /**
     * Retrieve pool by url
     *
     * @param string $url
     *
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
