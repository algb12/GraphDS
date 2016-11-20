<?php

namespace GraphDS\Edge;

class UndirectedEdge extends Edge
{
    /**
     * [__construct Constructor for UndirectedEdge object]
     * @param string $v1 ID of first vertex
     * @param string $v2 ID of second vertex
     */
    public function __construct($v1, $v2) {
        parent::__construct();
        $this->vertices[] = $v1;
        $this->vertices[] = $v2;
    }
}
