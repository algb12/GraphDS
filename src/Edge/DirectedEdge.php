<?php
/**
 * Directed edge.
 */
namespace GraphDS\Edge;

/**
 * Class defining a directed edge object.
 */
class DirectedEdge extends Edge
{
    /**
     * $value A value/weight held by the edge.
     *
     * @var double
     */
    public $value;
    /**
     * $vertices An array of vertices associated with the edge.
     *
     * @var array
     */
    public $vertices;

    /**
     * Constructor for DirectedEdge object.
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     * @param double $value   The value/weight the edge should hold
     */
    public function __construct($vertex1, $vertex2, $value = null)
    {
        parent::__construct();
        $this->vertices['out'] = $vertex1;
        $this->vertices['in'] = $vertex2;
        $this->value = $value;
    }
}
