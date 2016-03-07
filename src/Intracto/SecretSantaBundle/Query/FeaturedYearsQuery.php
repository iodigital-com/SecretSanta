<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class FeaturedYearsQuery
{
    private $dbal;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    /**
     * @return array
     */
    public function getFeaturedYears()
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('distinct(year(p.sentdate)) AS featured_year')
            ->from('Pool', 'p')
            ->where('year(p.sentdate) IS NOT NULL')
            ->orderBy('year(p.sentdate)', 'DESC');

        $yearsQuery = $query->execute()->fetchAll();

        $featuredYears = [];

        foreach ($yearsQuery as $f) {
            $checkDate = \DateTime::createFromFormat('Y-m-d', $f['featured_year'] . '-04-01');
            $dateNow = new \DateTime();

            if ($dateNow >= $checkDate) {
                array_push($featuredYears, $f['featured_year']);
            }
        }

        $featuredYears = array_reverse($featuredYears);

        return [
            'featured_years' => $featuredYears,
        ];
    }
}