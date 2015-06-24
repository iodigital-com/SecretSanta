<?php

namespace Intracto\SecretSantaBundle\Service;

use Doctrine\ORM\Internal\Hydration\IterableResult;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\VarDumper\VarDumper;

class ExportService
{
    /**
     * @param array $data
     * @param string $seperator
     * @param string $enclosure
     * @return StreamedResponse
     */
    public function exportCSV($data, $filename = 'export.csv', $seperator = ';', $enclosure = '"')
    {

        $response = new StreamedResponse(function () use ($data, $seperator, $enclosure) {
            $handle = fopen('php://output', 'r+');

            //Write header
            fputcsv($handle, array_keys($data[0]), $seperator, $enclosure);

            foreach ($data as $i => $row) {
                fputcsv($handle, $row, $seperator, $enclosure);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));


        return $response;

    }
}
