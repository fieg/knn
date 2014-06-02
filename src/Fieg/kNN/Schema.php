<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\kNN;

class Schema
{
    /**
     * @var string[]
     */
    protected $fields = array();

    /**
     * @param string $field
     * @return $this
     */
    public function addField($field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getFields()
    {
        return $this->fields;
    }
}
