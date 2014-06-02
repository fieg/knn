<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

use Fieg\kNN\Node;

class DataSetTest extends \PHPUnit_Framework_TestCase
{
    public function testGuess()
    {
        $schema = new \Fieg\kNN\Schema();
        $schema
            ->addField('rooms')
            ->addField('area')
        ;

        $dataset = new \Fieg\kNN\DataSet(3, $schema);

        $dataset->add(new Node(array('rooms' => 1, 'area' => 350, 'type' => 'apartment')));
        $dataset->add(new Node(array('rooms' => 2, 'area' => 300, 'type' => 'apartment')));
        $dataset->add(new Node(array('rooms' => 3, 'area' => 300, 'type' => 'apartment')));
        $dataset->add(new Node(array('rooms' => 4, 'area' => 250, 'type' => 'apartment')));

        $dataset->add(new Node(array('rooms' => 7, 'area' => 850, 'type' => 'house')));
        $dataset->add(new Node(array('rooms' => 7, 'area' => 900, 'type' => 'house')));
        $dataset->add(new Node(array('rooms' => 7, 'area' => 1200, 'type' => 'house')));
        $dataset->add(new Node(array('rooms' => 8, 'area' => 1500, 'type' => 'house')));

        $dataset->add(new Node(array('rooms' => 1, 'area' => 800, 'type' => 'flat')));
        $dataset->add(new Node(array('rooms' => 3, 'area' => 900, 'type' => 'flat')));
        $dataset->add(new Node(array('rooms' => 2, 'area' => 700, 'type' => 'flat')));
        $dataset->add(new Node(array('rooms' => 1, 'area' => 900, 'type' => 'flat')));

        $this->assertEquals('flat', $dataset->guess(new Node(array('rooms' => 4, 'area' => 900)), 'type'));
        $this->assertEquals('house', $dataset->guess(new Node(array('rooms' => 7, 'area' => 900)), 'type'));
        $this->assertEquals('apartment', $dataset->guess(new Node(array('rooms' => 1, 'area' => 200)), 'type'));
    }
}
