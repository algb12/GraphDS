<?php
/**
 * Undirected edge.
 */
namespace GraphDS\Edge;

/**
 * Class defining an undirected edge object.
 */
class UndirectedEdge extends Edge
{
    /**
     * $value A value/weight held by the edge.
     *
     * @var float
     */
    protected $value;
    /**
     * $vertices An array of vertices associated with the edge.
     *
     * @var array
     */
    public $vertices;

    /**
     * Constructor for UndirectedEdge object.
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
