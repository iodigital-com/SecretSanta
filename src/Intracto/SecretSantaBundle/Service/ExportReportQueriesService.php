<?php

namespace Intracto\SecretSantaBundle\Service;

class ExportReportQueriesService
{
    /** @var string */
    private $reportCachePath;

    /**
     * ExportReportQueriesService constructor.
     *
     * @param string $reportCachePath
     */
    public function __construct(string $reportCachePath)
    {
        $this->reportCachePath = $reportCachePath;
    }

    /**
     * @param array  $data
     * @param string $year
     */
    public function export(array $data, string $year): void
    {
        $filename = $this->getExportLocation($year).'/report.json';
        $dirname = \dirname($filename);

        if (!is_dir($dirname) && !mkdir($dirname, 0755, true) && !is_dir($dirname)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirname));
        }

        $handle = fopen($filename, 'w+');
        fwrite($handle, json_encode($data, JSON_PRETTY_PRINT));
        fclose($handle);
    }

    /**
     * @param string $year
     *
     * @return mixed
     */
    public function getReportQuery(string $year)
    {
        $filename = $this->getExportLocation($year).'/report.json';

        $handle = fopen($filename, 'r');
        $read = json_decode(fread($handle, filesize($filename)), true);
        fclose($handle); //Close up

        return $read;
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    private function getExportLocation(string $directory): string
    {
        return $this->reportCachePath.'/'.$directory;
    }
}
