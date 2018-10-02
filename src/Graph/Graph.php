<?php
/**
 * Graph.
 */
namespace GraphDS\Graph;

use GraphDS\Edge\Edge;
use GraphDS\Vertex\Vertex;

/**
 * Class defining a generic, extendable graph object.
 */
class Graph
{
    /**
     * An array holding all vertices of the graph.
     *
     * @var Vertex[][]
     */
    public $vertices = array();

    /**
     * An array holding all edges of the graph.
     *
     * @var Edge[][]
     */
    public $edges = array();

    /**
     * Defines whether the graph is directed or not.
     *
     * @var bool
     */
    public $directed;


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
