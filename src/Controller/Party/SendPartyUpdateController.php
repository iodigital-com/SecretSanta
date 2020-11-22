<?php

declare(strict_types=1);

namespace App\Controller\Party;

use App\Entity\Party;
use App\Mailer\MailerService;
use App\Query\ParticipantReportQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendPartyUpdateController extends AbstractController
{
    private TranslatorInterface $translator;

    /**
     * SendPartyUpdateController constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/send-party-update/{listurl}", name="send_party_update", methods={"GET"})
     */
    public function sendPartyUpdateAction(Party $party, ParticipantReportQuery $reportQueriesService, MailerService $mailerService)
    {
        $results = $reportQueriesService->fetchDataForPartyUpdateMail($party->getListurl());

        $mailerService->sendPartyUpdateMailForParty($party, $results);

        $this->addFlash('success', $this->translator->trans('flashes.send_party_update.success'));

        return $this->redirectToRoute('party_manage', ['listurl' => $party->getListurl()]);
    }
}
