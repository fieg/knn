<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\kNN;

class Node
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $neighbors;

    /**
     * @var int
     */
    protected $distance;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->neighbors = array();
        $this->distance = 1;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $neighbors
     * @return $this
     */
    public function setNeighbors($neighbors)
    {
        $this->neighbors = $neighbors;

        return $this;
    }

    /**
     * @return array
     */
    public function getNeighbors()
    {
        return $this->neighbors;
    }

    /**
     * @param $field
     * @return null
     */
    public function get($field)
    {
        if (!isset($this->data[$field])) {
            return null;
        }

        return $this->data[$field];
    }

    /**
     * @param $distance
     * @return $this
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
