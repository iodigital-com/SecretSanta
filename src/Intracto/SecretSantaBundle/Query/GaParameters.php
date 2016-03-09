<?php

namespace Intracto\SecretSantaBundle\Query;

class GaParameters
{
    private $analytics;
    private $viewId;
    private $start;
    private $end;
    private $metrics;

    public function __construct($analytics, $viewId, $start, $end, $metrics)
    {
        $this->analytics = $analytics;
        $this->viewId = $viewId;
        $this->start = $start;
        $this->end = $end;
        $this->metrics = $metrics;
    }

    /**
     * @return mixed
     */
    public function getAnalytics()
    {
        return $this->analytics;
    }

    /**
     * @return mixed
     */
    public function getViewId()
    {
        return $this->viewId;
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

    /**
     * @return mixed
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}