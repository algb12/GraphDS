<?php
/**
 * Graph
 */

namespace GraphDS\Graph;

/**
 * Class defining a generic, extendable graph object
 */
class Graph
{
    /**
     * An array holding all vertices of the graph
     * @var array
     */
    public $vertices;
    /**
     * An array holding all edges of the graph
     * @var array
     */
    public $edges;
    /**
     * Defines whether the graph is directed or not
     * @var boolean
     */
    public $directed;

    /**
     * Constructor for general Graph object
     */
    public function __construct() {
        $this->vertices = array();
        $this->edges = array();
    }
}
