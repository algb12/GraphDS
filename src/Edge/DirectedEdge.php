<?php
/**
 * Directed edge
 */

namespace GraphDS\Edge;

/**
 * Class defining a directed edge object
 */
class DirectedEdge extends Edge
{
    /**
     * $value A value held by the edge
     * @var mixed
     */
    public $value;
    /**
     * $vertices An array of vertices associated with the edge
     * @var array
     */
    public $vertices;

    /**
     * Constructor for DirectedEdge object
     * @param string $v1 ID of first vertex
     * @param string $v2 ID of second vertex
     */
    public function __construct($v1, $v2, $value = null) {
        parent::__construct();
        $this->vertices['out'] = $v1;
        $this->vertices['in'] = $v2;
        $this->value = $value;
    }
}
