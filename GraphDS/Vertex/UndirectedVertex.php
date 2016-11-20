<?php

namespace GraphDS\Vertex;

class UndirectedVertex extends Vertex
{
    /**
     * [__construct Constructor for UndirectedVertex object]
     */
    public function __construct() {
        parent::__construct();
        $this->neighbors = array();
    }

    /**
     * [addNeighbor Adds a neighboring, undirected vertex to this vertex]
     * @param string $v ID of vertex
     */
    public function addNeighbor($v) {
        $this->neighbors[] = $v;
    }

    /**
     * [getNeighbors Returns an array of all neighboring vertices of this vertex]
     * @return array Array of all neighboring vertices of this vertex
     */
    public function getNeighbors() {
        return $this->neighbors;
    }

    /**
     * [adjacent Checks if a given vertex is adjacent to this vertex]
     * @param  string  $v ID of vertex
     * @return boolean    Whether given vertex is adjacent to this vertex
     */
    public function adjacent($v) {
        return in_array($v, $this->neighbors);
    }
}
