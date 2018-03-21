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
     * Constructor for DirectedEdge object.
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     * @param float  $value   The value/weight the edge should hold
     */
    public function __construct($vertex1, $vertex2, $value = null)
    {
        parent::__construct();
        $this->vertices['from'] = $vertex1;
        $this->vertices['to'] = $vertex2;
        $this->value = $value;
    }
}
