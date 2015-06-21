<?php

namespace Intracto\SecretSantaBundle\Service;

use Doctrine\ORM\Internal\Hydration\IterableResult;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * @param IterableResult $data
     * @param string $seperator
     * @param string $enclosure
     * @return StreamedResponse
     */
    public function exportCSV(IterableResult $data, $filename = 'export.csv', $seperator = ';', $enclosure = ';')
    {
        $response = new StreamedResponse(function () use ($data, $seperator, $enclosure) {
            $handle = fopen('php://output', 'r+');

            $i = 0;
            while (false !== ($row = $data->next())) {

                $row = $row[0];
                //Write header
                if ($i == 0) {
                    fputcsv($handle, array_keys($row), $seperator, $enclosure);
                }
                fputcsv($handle, $row, $seperator, $enclosure);

                $i++;
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));

        return $response;

    }
}
