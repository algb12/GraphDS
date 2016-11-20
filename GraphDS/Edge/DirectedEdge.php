<?php

namespace GraphDS\Edge;

class DirectedEdge extends Edge
{
    /**
     * [__construct Constructor for DirectedEdge object]
     * @param string $v1 ID of first vertex
     * @param string $v2 ID of second vertex
     */
    public function __construct($v1, $v2) {
        parent::__construct();
        $this->vertices['out'] = $v1;
        $this->vertices['in'] = $v2;
    }
}
