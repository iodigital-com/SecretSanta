<?php

namespace App\Query;

use Doctrine\DBAL\Connection;

class FeaturedYearsQuery
{
    private Connection $dbal;

    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    public function getFeaturedYears(): array
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('distinct(year(p.sent_date)) AS featured_year')
            ->from('party', 'p')
            ->where('year(p.sent_date) IS NOT NULL')
            ->orderBy('year(p.sent_date)', 'DESC');

        $yearsQuery = $query->execute()->fetchAll();

        $featuredYears = [];

        foreach ($yearsQuery as $f) {
            $checkDate = \DateTime::createFromFormat('Y-m-d', $f['featured_year'].'-04-01');
            $dateNow = new \DateTime();

            if ($dateNow >= $checkDate) {
                $featuredYears[] = $f['featured_year'];
            }
        }

        $featuredYears = array_reverse($featuredYears);

        return [
            'featured_years' => $featuredYears,
        ];
    }
}
