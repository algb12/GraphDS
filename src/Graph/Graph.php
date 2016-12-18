<?php
/**
 * Graph.
 */
namespace GraphDS\Graph;

/**
 * Class defining a generic, extendable graph object.
 */
class Graph
{
    /**
     * An array holding all vertices of the graph.
     *
     * @var array
     */
    public $vertices;
    /**
     * A running count of all the vertices.
     *
     * @var int
     */
    public $vertexCount;
    /**
     * An array holding all edges of the graph.
     *
     * @var array
     */
    public $edges;
    /**
     * A running count of all the edges.
     *
     * @var int
     */
    public $edgeCount;
    /**
     * Defines whether the graph is directed or not.
     *
     * @var bool
     */
    public $directed;

    /**
     * Constructor for general Graph object.
     */
    public function __construct()
    {
        $this->vertices = array();
        $this->vertexCount = 0;
        $this->edges = array();
        $this->edgeCount = 0;
    }
}
