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
     * An array holding all edges of the graph.
     *
     * @var array
     */
    public $edges;
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
        $this->edges = array();
    }

    /**
     * Returns the number of vertices in the graph.
     *
     * @return int Number of vertices in the graph
     */
    public function getVertexCount()
    {
        return count($this->vertices);
    }

    /**
     * Returns the number of edges in the graph.
     *
     * @return int Number of edges in the graph
     */
    public function getEdgeCount()
    {
        $count = 0;
        foreach ($this->edges as $edgesFrom) {
            $count += count($edgesFrom);
        }

        return $count;
    }
}
