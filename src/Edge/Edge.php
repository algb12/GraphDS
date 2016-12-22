<?php
/**
 * Edge.
 */
namespace GraphDS\Edge;

/**
 * Class defining a generic, extendable edge object.
 */
class Edge
{
    /**
     * $value A value/weight held by the edge.
     *
     * @var double
     */
    protected $value;
    /**
     * $vertices An array of vertices associated with the edge.
     *
     * @var array
     */
    public $vertices;

    /**
     * Constructor for general Edge object.
     */
    public function __construct()
    {
        $this->value = null;
        $this->vertices = array();
    }

    /**
     * Returns value/weight associated with this edge.
     *
     * @return double Value/weight associated with this edge
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value/weight associated with this edge.
     *
     * @param double $value Value/weight to be associated with this edge
     */
    public function setValue($value = null)
    {
        $this->value = (double) $value;
    }

    /**
     * Returns an array of vertices connected by this edge.
     *
     * @return array Vertices connected by this edge
     */
    public function getConnectedVertices()
    {
        return $this->vertices;
    }
}
