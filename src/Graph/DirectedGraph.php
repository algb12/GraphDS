<?php
/**
 * Directed graph.
 */
namespace GraphDS\Graph;

use GraphDS\Vertex\DirectedVertex;
use GraphDS\Edge\DirectedEdge;
use InvalidArgumentException;

/**
 * Class defining a directed graph object.
 */
class DirectedGraph extends Graph
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
     * Constructor for DirectedGraph object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->directed = true;
    }

    /**
     * Adds a directed vertex to the graph.
     *
     * @param string $v ID of the vertex
     */
    public function addVertex($vertex)
    {
        if (empty($this->vertices[$vertex])) {
            $this->vertices[$vertex] = new DirectedVertex();
        }
    }

    /**
     * Removes a directed vertex from the graph.
     *
     * @param string $v ID of the vertex
     */
    public function removeVertex($vertex)
    {
        if (empty($this->vertices[$vertex])) {
            throw new InvalidArgumentException("Vertex $vertex does not exist.");
        }
        $neighbors = $this->vertices[$vertex]->getNeighbors();
        foreach ($neighbors['out'] as $neighbor) {
            if (($key = array_search($vertex, $this->vertices[$neighbor]->neighbors['in'])) !== false) {
                unset($this->vertices[$neighbor]->neighbors['in'][$key]);
            }
        }
        foreach ($neighbors['in'] as $neighbor) {
            if (($key = array_search($vertex, $this->vertices[$neighbor]->neighbors['out'])) !== false) {
                unset($this->vertices[$neighbor]->neighbors['out'][$key]);
            }
            if ($this->edge($neighbor, $vertex)) {
                $this->removeEdge($neighbor, $vertex);
            }
        }
        unset($this->edges[$vertex]);
        unset($this->vertices[$vertex]);
    }

    /**
     * Returns an edge object in the graph from $vertex1 to $vertex2.
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     *
     * @return object Instance of DirectedEdge from $vertex1 to $vertex2
     */
    public function edge($vertex1, $vertex2)
    {
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (isset($this->edges[$vertex1][$vertex2])) {
            return $this->edges[$vertex1][$vertex2];
        }
    }

    /**
     * Adds a directed edge between two directed vertices ($vertex1 to $vertex2).
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     * @param float  $value   The value/weight the edge should hold
     */
    public function addEdge($vertex1, $vertex2, $value = null)
    {
        if ($vertex1 === $vertex2) {
            throw new InvalidArgumentException('Cannot connect vertex to itself.');
        }
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (null === $this->edge($vertex1, $vertex2)) {
            $this->edges[$vertex1][$vertex2] = new DirectedEdge($vertex1, $vertex2, $value);
            $this->vertices[$vertex1]->addOutNeighbor($vertex2);
            $this->vertices[$vertex2]->addInNeighbor($vertex1);
        }
    }

    /**
     * Removes a directed edge between two directed vertices ($vertex1 to $vertex2).
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     */
    public function removeEdge($vertex1, $vertex2)
    {
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (null === $this->edge($vertex1, $vertex2)) {
            throw new InvalidArgumentException("No edge from $vertex1 to $vertex2.");
        }
        $this->vertices[$vertex1]->removeOutNeighbor($vertex2);
        $this->vertices[$vertex2]->removeInNeighbor($vertex1);
        unset($this->edges[$vertex1][$vertex2]);
        if (empty($this->edges[$vertex1])) {
            unset($this->edges[$vertex1]);
        }
    }

    /**
     * Returns the value associated with the edge between two directed vertices (e.g. cost, $vertex1 to $vertex2).
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     *
     * @return string Value associated with the edge between $vertex1 and $vertex2
     */
    public function getEdgeValue($vertex1, $vertex2)
    {
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (null === $this->edge($vertex1, $vertex2)) {
            throw new InvalidArgumentException("No edge from $vertex1 to $vertex2.");
        }

        return $this->edge($vertex1, $vertex2)->getValue();
    }

    /**
     * Sets the value associated with the edge between two directed vertices (e.g. cost, $vertex1 to $vertex2).
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     * @param string $value   Value to be set for the edge between $vertex1 and $vertex2
     */
    public function setEdgeValue($vertex1, $vertex2, $value)
    {
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (null === $this->edge($vertex1, $vertex2)) {
            throw new InvalidArgumentException("No edge from $vertex1 to $vertex2.");
        }
        $this->edge($vertex1, $vertex2)->setValue($value);
    }
}
