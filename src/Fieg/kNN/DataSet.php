<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\kNN;

class DataSet
{
    /**
     * @var Node[]
     */
    protected $nodes;

    /**
     * @var int
     */
    protected $k;

    /**
     * @var array<int,int>
     */
    protected $ranges;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * Constructor.
     *
     * @param int    $k
     * @param Schema $schema
     */
    public function __construct($k = 3, Schema $schema)
    {
        $this->k = $k;
        $this->nodes = array();
        $this->ranges = array();
        $this->schema = $schema;
    }

    /**
     * @param Node $node
     * @return $this
     */
    public function add(Node $node)
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * @param  Node   $node
     * @param  string $field
     * @return mixed
     */
    public function guess(Node $node, $field)
    {
        $neighbors = $this->nodes;
        $this->calculateDistances($node, $neighbors);

        $this->sort($neighbors);

        $hits = array();

        /** @var Node[] $nearest */
        $nearest = array_slice($neighbors, 0, $this->k);
        foreach ($nearest as $neighbor) {
            if (!isset($hits[$neighbor->get($field)])) {
                $hits[$neighbor->get($field)] = 0;
            }

            $hits[$neighbor->get($field)] += 1;
        }

        $guess = array('value' => false, 'hits' => 0);

        foreach ($hits as $value => $count) {
            if ($count > $guess['hits']) {
                $guess = array('value' => $value, 'hits' => $count);
            }
        }

        return $guess['value'];
    }

    /**
     * Calculates ranges for each field
     */
    protected function calculateRanges()
    {
        $this->ranges = array();

        foreach ($this->schema->getFields() as $field) {
            $min = INF;
            $max = 0;

            foreach ($this->nodes as $node) {
                if ($node->get($field) < $min) {
                    $min = $node->get($field);
                }

                if ($node->get($field) > $max) {
                    $max = $node->get($field);
                }
            }

            $this->ranges[$field] = array($min, $max);
        }
    }

    /**
     * Sorts nodes by distance
     *
     * @param Node[] $nodes
     */
    protected function sort(array &$nodes)
    {
        usort(
            $nodes,
            function (Node $a, Node $b) {
                return $a->getDistance() > $b->getDistance();
            }
        );
    }

    /**
     * Calculates distances
     *
     * @param Node   $node
     * @param Node[] $neighbors
     */
    protected function calculateDistances(Node $node, array $neighbors)
    {
        $this->calculateRanges();

        foreach ($neighbors as $neighbor) {
            $deltas = array();

            foreach ($this->schema->getFields() as $field) {
                list($min, $max) = $this->ranges[$field];
                $range = $max - $min;

                $delta = $neighbor->get($field) - $node->get($field);
                $delta = $delta / $range;

                $deltas[$field] = $delta;
            }

            $total = 0;
            foreach ($deltas as $delta) {
                $total += $delta * $delta;
            }

            $neighbor->setDistance(sqrt($total));
        }
    }
}
