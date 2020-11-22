<?php

namespace Intracto\SecretSantaBundle\Query;

class Season
{
    private \DateTime $start;
    private \DateTime $end;

    public function __construct(?int $year = null)
    {
        $this->start = \DateTime::createFromFormat('Y-m-d', '2006-04-01');
        $this->end = new \DateTime();

        if ($year) {
            $this->start = \DateTime::createFromFormat('Y-m-d', $year.'-04-01');
            $this->end = \DateTime::createFromFormat('Y-m-d', $year + 1 .'-04-01');
        }
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function getEnd(): \DateTime
    {
        return $this->end;
    }
}
