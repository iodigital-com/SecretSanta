<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\Type\PartyType;
use App\Entity\Party;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GeoIp2\Database\Reader;

class HomepageController extends AbstractController
{
    public function __construct(
        private string $geoIpDbPath,
    ) {}

    /**
     * @Route("/", name="homepage", methods={"GET"})
     * @Template("Party/create.html.twig")
     */
    public function indexAction(Request $request)
    {
        $partyForm = $this->createForm(PartyType::class, new Party(), [
            'action' => $this->generateUrl('create_party'),
        ]);

        $geoCountry = '';
        $reader = new Reader($this->geoIpDbPath);
        try {
            $geoInformation = $reader->city($request->getClientIp());
            $geoCountry = $geoInformation->country->isoCode;
        } catch (\Exception) {}

        return [
            'form' => $partyForm->createView(),
            'geoCountry' => $geoCountry,
        ];
    }
}
