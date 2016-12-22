<?php
/**
 * Vertex.
 */
namespace GraphDS\Vertex;

/**
 * Class defining a generic, extendable vertex object.
 */
class Vertex
{
    /**
     * Variable holding the value of this vertex.
     *
     * @var mixed
     */
    protected $value;
    /**
     * Array holding references to all neighboring vertices of this vertex.
     *
     * @var array
     */
    public $neighbors;

    /**
     * Constructor for general Vertex object.
     */
    public function __construct()
    {
        $this->value = null;
        $this->neighbors = array();
    }

    /**
     * Gets the value associated with this vertex.
     *
     * @return mixed Value associated with this vertex
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value associated with this vertex.
     *
     * @param mixed $value Value to be associated with this vertex
     */
    public function setValue($value = null)
    {
        $this->value = $value;
    }
}
