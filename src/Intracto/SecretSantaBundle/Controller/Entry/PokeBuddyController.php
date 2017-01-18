<?php

namespace Intracto\SecretSantaBundle\Controller\Entry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PokeBuddyController extends Controller
{
    /**
     * @Route("/poke/{url}/{entryId}", name="poke_buddy")
     */
    public function pokeAction($url, $entryId)
    {
        $entry = $this->get('entry_repository')->find($entryId);

        $this->get('intracto_secret_santa.mail')->sendPokeMailToBuddy($entry);

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('flashes.entry.poke_buddy')
        );

        return $this->redirect($this->generateUrl('entry_view', ['url' => $url]));
    }
}
