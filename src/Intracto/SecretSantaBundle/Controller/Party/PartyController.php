<?php

namespace Intracto\SecretSantaBundle\Controller\Party;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Intracto\SecretSantaBundle\Mailer\MailerService;
use Intracto\SecretSantaBundle\Entity\Party;
use Intracto\SecretSantaBundle\Form\Type\PartyExcludeParticipantType;
use Intracto\SecretSantaBundle\Form\Type\PartyType;

class PartyController extends Controller
{
    /**
     * @Route("/party/create", name="create_party")
     * @Method("POST")
     * @Template("IntractoSecretSantaBundle:Pool:create.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->handlePartyCreation(
            $request,
            new Party()
        );
    }

    /**
     * @Route("/created/{listUrl}", name="party_created")
     * @Template("IntractoSecretSantaBundle:Pool:created.html.twig")
     */
    public function createdAction($listUrl)
    {
        $party = $this->getParty($listUrl);
        if (!$party->getCreated()) {
            return $this->redirect($this->generateUrl('party_exclude', ['listUrl' => $party->getListurl()]));
        }

        return [
            'party' => $party,
        ];
    }

    /**
     * @Route("/exclude/{listUrl}", name="party_exclude")
     * @Template("IntractoSecretSantaBundle:Pool:exclude.html.twig")
     */
    public function excludeAction(Request $request, $listUrl)
    {
        /** @var MailerService $mailerService */
        $mailerService = $this->get('intracto_secret_santa.mail');
        $party = $this->getParty($listUrl);

        if ($party->getCreated()) {
            $mailerService->sendPendingConfirmationMail($party);

            return $this->redirect($this->generateUrl('party_created', ['listUrl' => $party->getListurl()]));
        }

        if ($party->getParticipants()->count() <= 3) {
            $party->setCreated(true);
            $this->get('doctrine.orm.entity_manager')->persist($party);

            $this->get('intracto_secret_santa.participant_service')->shuffleParticipants($party);

            $this->get('doctrine.orm.entity_manager')->flush();

            $mailerService->sendPendingConfirmationMail($party);

            return $this->redirect($this->generateUrl('party_created', ['listUrl' => $party->getListurl()]));
        }

        $form = $this->createForm(PartyExcludeParticipantType::class, $party);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $party->setCreated(true);
                $this->get('doctrine.orm.entity_manager')->persist($party);

                $this->get('intracto_secret_santa.participant_service')->shuffleParticipants($party);

                $this->get('doctrine.orm.entity_manager')->flush();

                $mailerService->sendPendingConfirmationMail($party);

                return $this->redirect($this->generateUrl('party_created', ['listUrl' => $party->getListurl()]));
            }
        }

        return [
            'form' => $form->createView(),
            'party' => $party,
        ];
    }

    /**
     * @Route("/reuse/{listUrl}", name="party_reuse")
     * @Template("IntractoSecretSantaBundle:Pool:create.html.twig")
     */
    public function reuseAction(Request $request, $listUrl)
    {
        $party = $this->getParty($listUrl);
        $party = $party->createNewPartyForReuse();

        return $this->handlePartyCreation($request, $party);
    }

    /**
     * @Route("/delete/{listUrl}", name="party_delete")
     * @Template("IntractoSecretSantaBundle:Pool:deleted.html.twig")
     */
    public function deleteAction(Request $request, $listUrl)
    {
        $correctCsrfToken = $this->isCsrfTokenValid(
            'delete_pool',
            $request->get('csrf_token')
        );
        $correctConfirmation = (strtolower($request->get('confirmation')) === strtolower($this->get('translator')->trans('pool_manage_valid.delete.phrase_to_type')));

        if ($correctConfirmation === false || $correctCsrfToken === false) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('flashes.pool.not_deleted')
            );

            return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
        }

        $party = $this->getParty($listUrl);

        $this->get('doctrine.orm.entity_manager')->remove($party);
        $this->get('doctrine.orm.entity_manager')->flush();
    }

    /**
     * @param Request $request
     * @param Party   $party
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function handlePartyCreation(Request $request, Party $party)
    {
        $form = $this->createForm(
            PartyType::class,
            $party,
            [
                'action' => $this->generateUrl('create_party'),
            ]
        );

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                foreach ($party->getParticipants() as $participant) {
                    $participant->setParty($party);
                }

                $dateFormatter = \IntlDateFormatter::create(
                    $request->getLocale(),
                    \IntlDateFormatter::MEDIUM,
                    \IntlDateFormatter::NONE
                );

                $message = $this->get('translator')->trans('pool_controller.created.message', [
                    '%amount%' => $party->getAmount(),
                    '%eventdate%' => $dateFormatter->format($party->getEventdate()->getTimestamp()),
                    '%location%' => $party->getLocation(),
                    '%message%' => $party->getMessage(),
                ]);

                $party->setCreationDate(new \DateTime());
                $party->setMessage($message);
                $party->setLocale($request->getLocale());

                $this->get('doctrine.orm.entity_manager')->persist($party);
                $this->get('doctrine.orm.entity_manager')->flush();

                return $this->redirect($this->generateUrl('party_exclude', ['listUrl' => $party->getListurl()]));
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Retrieve party by url.
     *
     * @param $listUrl
     *
     * @return Party
     *
     * @throws NotFoundHttpException
     */
    private function getParty($listUrl)
    {
        /** @var \Intracto\SecretSantaBundle\Entity\PartyRepository $pool */
        $party = $this->get('party_repository')->findOneByListurl($listUrl);
        if ($party === null) {
            throw new NotFoundHttpException();
        }

        return $party;
    }
}
