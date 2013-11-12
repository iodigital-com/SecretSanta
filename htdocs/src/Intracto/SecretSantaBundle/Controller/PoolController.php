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
            $form->handleRequest($request);
            if ($form->isValid()) {
                foreach ($pool->getEntries() as $entry) {
                    $entry->setPool($pool);
                }

                $em = $this->getDoctrine()->getManager();
                $message = "Hi there (NAME),\n\n";
                $message .= "(ADMINISTRATOR) created a Secret Santa event and has listed you as a participant.\n\n";
                $message .= "Join the Secret Santa fun and find out who your gift buddy is by clicking the button below.\n\n";
                $message .= "You can spend up to " . $pool->getAmount() .
                    " for your gift. But of course creating your own present is allowed. Even encouraged!\n\n";
                $message .= "The Secret Santa party is planned " .
                    $pool->getDate()->format("F jS") .
                    ". Be sure to bring your gift!\n";
                $message .= $pool->getMessage() . "\n";
                $message .= "\n\nMerry Christmas!";
                $pool->setMessage($message);
                $em->persist($pool);
                $em->flush();

                // Send pending confirmation mail
                $message = \Swift_Message::newInstance()
                    ->setSubject('Secret Santa Confirmation')
                    ->setFrom($this->adminEmail, "Santa Claus")
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
                    );
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
     * @Route("/manage/{listUrl}", name="pool_manage")
     * @Template()
     */
    public function manageAction($listUrl)
    {
        $this->getPool($listUrl);

        if ($this->pool->getSentdate() === null) {
            $this->get('session')->getFlashBag()->add(
                'success',
                '<strong>Perfect!</strong><br/>Your email is now validated.<br/>
                            Our gnomes are travelling on the internet as we speak, delivering all your soon-to-be-Secret-Santas their gift buddies.<br/>'
            );
            /** @var \Intracto\SecretSantaBundle\Entity\EntryService $entryService */
            $entryService = $this->get('intracto_secret_santa.entry_service');

            $entryService->shuffleEntries($this->pool);
            $entryService->sendSecretSantaMailsForPool($this->pool);
        }

        return array('pool' => $this->pool);
    }

    /**
     * @Route("/resend/{listUrl}/{entryId}", name="pool_resend")
     * @Template("IntractoSecretSantaBundle:Pool:manage.html.twig")
     */
    public function resendAction($listUrl, $entryId)
    {
        $entry = $this->getDoctrine()->getRepository('IntractoSecretSantaBundle:Entry')->find($entryId);

        if (!is_object($entry)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        if ($entry->getPool()->getListUrl() !== $listUrl) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        $entryService = $this->get('intracto_secret_santa.entry_service');
        $entryService->sendSecretSantaMailForEntry($entry);

        $this->get('session')->getFlashBag()->add(
            'success',
            '<strong>Resent!</strong><br/>The e-mail to ' . $entry->getName() . ' has been resent.<br/>'
        );

        return $this->redirect($this->generateUrl('pool_manage', array('listUrl' => $listUrl)));
    }

    /**
     * Retrieve pool by url
     *
     * @param $listurl
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @internal param string $url
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
