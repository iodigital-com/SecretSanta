<?php

namespace App\Query;

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

    public function getAnalytics()
    {
        return $this->analytics;
    }

    public function getViewId()
    {
        return $this->viewId;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function getMetrics()
    {
        return $this->metrics;
    }
}
