<?php

namespace Intracto\SecretSantaBundle\Query;


class Period
{
    private $start;
    private $end;

    /**
     * Period constructor.
     * @param $start
     * @param $end
     */
    public function __construct($end, $start = null)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }
}