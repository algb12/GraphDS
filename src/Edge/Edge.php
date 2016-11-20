<?php

namespace GraphDS\Edge;

class Edge
{
    /**
     * [__construct Constructor for general Edge object]
     */
    public function __construct() {
        $this->value = null;
        $this->vertices = array();
    }

    /**
     * [getValue Returns value associated with this edge (e.g. cost)]
     * @return mixed Value associated with this edge
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * [setValue Sets the value associated with this edge (e.g. cost)]
     * @param mixed $value Value to be associated with this edge
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * [getConnectedVertices Returns an array of vertices connected by this edge]
     * @return array Vertices connected by this edge
     */
    public function getConnectedVertices() {
        return $this->vertices;
    }
}
