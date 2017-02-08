<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResendEntryController extends Controller
{
    /**
     * @Route("/resend/{listUrl}/{entryId}", name="resend_entry")
     */
    public function resendAction($listUrl, $entryId)
    {
        $entry = $this->get('entry_repository')->find($entryId);
        if ($entry === null) {
            throw new NotFoundHttpException();
        }

        if ($entry->getPool()->getListUrl() !== $listUrl) {
            throw new NotFoundHttpException();
        }

        $this->get('intracto_secret_santa.mail')->sendSecretSantaMailForEntry($entry);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.resend_entry.resent', ['%email%' => $entry->getName()])
        );

        return $this->redirect($this->generateUrl('pool_manage', ['listUrl' => $listUrl]));
    }
}