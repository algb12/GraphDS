<?php

namespace GraphDS\Vertex;

class Vertex
{
    /**
     * [__construct Constructor for general Vertex object]
     */
    public function __construct() {
        $this->value = null;
        $this->neighbors = array();
    }

    /**
     * [getValue Gets the value associated with this vertex]
     * @return mixed Value associated with this vertex
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * [setValue Sets the value associated with this vertex]
     * @param mixed $value Value to be associated with this vertex
     */
    public function setValue($value) {
        $this->value = $value;
    }
}
