<?php

namespace Intracto\SecretSantaBundle\Controller;

use Intracto\SecretSantaBundle\Event\PoolEvent;
use Intracto\SecretSantaBundle\Event\PoolEvents;
use Intracto\SecretSantaBundle\Form\PoolExcludeEntryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityRepository;
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
     * @DI\Inject("pool_repository")
     * @var EntityRepository
     */
    public $poolRepository;

    /**
     * @DI\Inject("entry_repository")
     * @var EntityRepository
     */
    public $entryRepository;

    /**
     * @DI\Inject("event_dispatcher")
     * @var EventDispatcherInterface
     */
    public $eventDispatcher;

    /**
     * @var Pool
     */
    private $pool;

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
                    ". Be sure to bring your gift!\n\n";
                $message .= $pool->getMessage() . "\n";
                $message .= "\n\nMerry Christmas!";
                $pool->setMessage($message);
                $em->persist($pool);
                $em->flush();

                return $this->redirect($this->generateUrl('pool_exclude', array('listUrl' => $pool->getListurl())));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/exclude/{listUrl}", name="pool_exclude")
     * @Template()
     */
    public function excludeAction(Request $request, $listUrl)
    {
        $this->getPool($listUrl);
        $this->redirectIfNotAllowed();

        $form = $this->createForm(new PoolExcludeEntryType(), $this->pool);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($this->pool);
                $em->flush();

                $this->eventDispatcher->dispatch(
                    PoolEvents::NEW_POOL_CREATED,
                    new PoolEvent($this->pool)
                );

                return new Response($this->renderView('IntractoSecretSantaBundle:Pool:created.html.twig', array('pool' => $this->pool)));
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
                "<strong>Perfect!</strong><br/>Your email is now validated.<br/>
                    Our gnomes are travelling on the internet as we speak, delivering all your soon-to-be-Secret-Santas their gift buddies.<br/>
                    <br />
                    Don't forget to confirm your own participation. We've sent you another email. Go check it out!"
            );

            /** @var \Intracto\SecretSantaBundle\Entity\EntryService $entryService */
            $entryService = $this->get('intracto_secret_santa.entry_service');

            $entryService->shuffleEntries($this->pool);
            $entryService->sendSecretSantaMailsForPool($this->pool);
        }

        return array(
            'pool' => $this->pool,
            'delete_pool_csrf_token' => $this->get('form.csrf_provider')->generateCsrfToken('delete_pool')
        );
    }

    /**
     * @Route("/delete/{listUrl}", name="pool_delete")
     * @Template()
     */
    public function deleteAction($listUrl)
    {
        $correctCsrfToken = $this->get('form.csrf_provider')->isCsrfTokenValid(
            'delete_pool',
            $this->getRequest()->get('csrf_token')
        );
        $correctConfirmation = ($this->getRequest()->get('confirmation') === 'delete everything');

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'error',
                '<h4>Not deleted</h4> The confirmation was incorrect.'
            );

            return $this->redirect($this->generateUrl('pool_manage', array('listUrl' => $listUrl)));
        }

        $em = $this->getDoctrine()->getManager();
        $this->getPool($listUrl);

        $em->remove($this->pool);
        $em->flush();
    }

    /**
     * @Route("/resend/{listUrl}/{entryId}", name="pool_resend")
     * @Template("IntractoSecretSantaBundle:Pool:manage.html.twig")
     */
    public function resendAction($listUrl, $entryId)
    {
        $entry = $this->entryRepository->find($entryId);

        if (!is_object($entry)) {
            throw new NotFoundHttpException();
        }

        if ($entry->getPool()->getListUrl() !== $listUrl) {
            throw new NotFoundHttpException();
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
     * @throws NotFoundHttpException
     * @internal param string $url
     *
     * @return boolean
     */
    protected function getPool($listurl)
    {
        $this->pool = $this->poolRepository->findOneByListurl($listurl);

        if (!is_object($this->pool)) {
            throw new NotFoundHttpException();
        }

        return true;
    }

    private function redirectIfNotAllowed()
    {
        $request = $this->container->get('request');
        $routeName = $request->get('_route');
        /*
        if($route$this->pool->getConfirmed() && $this->pool->getSentdate() === null){

        }
        if($route)
        */

        return true;
    }
}
