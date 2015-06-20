<?php

namespace Intracto\SecretSantaBundle\Model;

use Symfony\Component\HttpFoundation\Request;

class AnalyticsOptions
{
    private $dimension;
    private $metric;
    private $sort_metric = null;
    private $filter = null;
    private $start_date;
    private $end_date;
    private $start_index = 1;
    private $max_results = 30;

    public function __construct()
    {
        $this->dimension = 'country';
        $this->metric = 'visits';
        $this->sort_metric = '-visits';
        $this->filter = null;
        $this->start_date = date('Y-m-d', strtotime('-1 month'));
        $this->end_date = date('Y-m-d');
        $this->start_index = 1;
        $this->max_results = 10;
    }

    public function readableRequest()
    {
        return ucfirst($this->metric).'/'.ucfirst($this->dimension). ' from '.$this->start_date.' to '.$this->end_date;
    }

    public function loadFromRequest(Request $request)
    {
        $this->start_date = $request->get('start_date', date('Y-m-d', strtotime('-1 month')));
        $this->end_date = $request->get('end_date', date('Y-m-d'));
        $this->max_results = $request->get('max_results', 10);
    }

    public function uniqueCachingString()
    {
        $uniqueData = $this->dimension.'_'.$this->start_date.'_'.$this->end_date.'_'.$this->max_results;

        return md5($uniqueData);
    }

    public function getDimensions()
    {
        return array($this->dimension);
    }

    public function getMetrics()
    {
        return array($this->metric);
    }

    /**
     * @return mixed
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * @param mixed $dimension
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;
    }

    /**
     * @return mixed
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @param mixed $metric
     */
    public function setMetric($metric)
    {
        $this->metric = $metric;
    }

    /**
     * @return null
     */
    public function getSortMetric()
    {
        return $this->sort_metric;
    }

    /**
     * @param null $sort_metric
     */
    public function setSortMetric($sort_metric)
    {
        $this->sort_metric = $sort_metric;
    }

    /**
     * @return null
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param null $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return null
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param null $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * @return null
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param null $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }

    /**
     * @return int
     */
    public function getStartIndex()
    {
        return $this->start_index;
    }

    /**
     * @param int $start_index
     */
    public function setStartIndex($start_index)
    {
        $this->start_index = $start_index;
    }

    /**
     * @return int
     */
    public function getMaxResults()
    {
        return $this->max_results;
    }

    /**
     * @param int $max_results
     */
    public function setMaxResults($max_results)
    {
        $this->max_results = $max_results;
    }
}