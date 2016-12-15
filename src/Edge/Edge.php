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
     * $value A value held by the edge.
     *
     * @var mixed
     */
    public $value;
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
     * Returns value associated with this edge (e.g. cost).
     *
     * @return mixed Value associated with this edge
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value associated with this edge (e.g. cost).
     *
     * @param mixed $value Value to be associated with this edge
     */
    public function setValue($value = null)
    {
        if (empty($value)) {
            trigger_error('No value given. Assuming null.', E_USER_NOTICE);
        }
        $this->value = $value;
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
