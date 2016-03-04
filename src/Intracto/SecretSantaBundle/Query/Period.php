<?php
/**
 * Created by PhpStorm.
 * User: nielsroels
 * Date: 3/03/16
 * Time: 16:23
 */

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
    public function __construct($start, $end = null)
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