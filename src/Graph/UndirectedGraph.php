<?php
/**
 * Undirected graph.
 */
namespace GraphDS\Graph;

use GraphDS\Vertex\UndirectedVertex;
use GraphDS\Edge\UndirectedEdge;
use InvalidArgumentException;

/**
 * Class defining an undirected graph object.
 */
class UndirectedGraph extends Graph
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
     * Constructor for UndirectedGraph object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->directed = false;
    }

    /**
     * Adds an undirected vertex to the graph.
     *
     * @param string $v ID of the vertex
     */
    public function addVertex($vertex)
    {
        if (empty($this->vertices[$vertex])) {
            $this->vertices[$vertex] = new UndirectedVertex();
        }
    }

    /**
     * Removes an undirected vertex from the graph.
     *
     * @param string $vertex ID of the vertex
     */
    public function removeVertex($vertex)
    {
        if (empty($this->vertices[$vertex])) {
            throw new InvalidArgumentException("Vertex $vertex does not exist.");
        }
        $neighbors = $this->vertices[$vertex]->getNeighbors();
        foreach ($neighbors as $neighbor) {
            if (($key = array_search($vertex, $this->vertices[$neighbor]->neighbors)) !== false) {
                unset($this->vertices[$neighbor]->neighbors[$key]);
            }
            if (isset($this->edges[$neighbor][$vertex])) {
                $this->removeEdge($neighbor, $vertex);
            }
        }
        unset($this->edges[$vertex]);
        unset($this->vertices[$vertex]);
    }

    /**
     * Adds an undirected edge between two undirected vertices.
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     * @param mixed  $value   The value the edge should hold
     */
    public function addEdge($vertex1, $vertex2, $value = null)
    {
        if ($vertex1 === $vertex2) {
            throw new InvalidArgumentException('Cannot connect vertex to itself.');
        }
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (empty($this->edges[$vertex1][$vertex2]) || empty($this->edges[$vertex2][$vertex1])) {
            $this->edges[$vertex1][$vertex2] = new UndirectedEdge($vertex1, $vertex2, $value);
            $this->edges[$vertex2][$vertex1] = $this->edges[$vertex1][$vertex2];
            $this->vertices[$vertex1]->addNeighbor($vertex2);
            $this->vertices[$vertex2]->addNeighbor($vertex1);
        }
    }

    /**
     * Removes an undirected edge between two undirected vertices.
     *
     * @param string $vertex1 ID of first undirected vertex
     * @param string $vertex2 ID of second undirected vertex
     */
    public function removeEdge($vertex1, $vertex2)
    {
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (empty($this->edges[$vertex1][$vertex2]) && empty($this->edges[$vertex2][$vertex1])) {
            throw new InvalidArgumentException("No edge between $vertex1 and $vertex2.");
        }
        $this->vertices[$vertex1]->removeNeighbor($vertex2);
        $this->vertices[$vertex2]->removeNeighbor($vertex1);
        if (isset($this->edges[$vertex1][$vertex2]) && isset($this->edges[$vertex2][$vertex1])) {
            unset($this->edges[$vertex1][$vertex2]);
            if (empty($this->edges[$vertex1])) {
                unset($this->edges[$vertex1]);
            }
            unset($this->edges[$vertex2][$vertex1]);
            if (empty($this->edges[$vertex2])) {
                unset($this->edges[$vertex2]);
            }
        }
    }

    /**
     * Returns the value associated with the edge between two undirected vertices (e.g. cost).
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
        if (empty($this->edges[$vertex1][$vertex2]) && empty($this->edges[$vertex2][$vertex1])) {
            throw new InvalidArgumentException("No edge between $vertex1 and $vertex2.");
        }
        if (isset($this->edges[$vertex1][$vertex2])) {
            return $this->edges[$vertex1][$vertex2]->getValue();
        } elseif (isset($this->edges[$vertex2][$vertex1])) {
            return $this->edges[$vertex2][$vertex1]->getValue();
        }
    }

    /**
     * Sets the value associated with the edge between two undirected vertices (e.g. cost).
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
        if (empty($this->edges[$vertex1][$vertex2]) && empty($this->edges[$vertex2][$vertex1])) {
            throw new InvalidArgumentException("No edge between $vertex1 and $vertex2.");
        }
        if (isset($this->edges[$vertex1][$vertex2])) {
            $this->edges[$vertex1][$vertex2]->setValue($value);
        } elseif (isset($this->edges[$vertex2][$vertex1])) {
            $this->edges[$vertex2][$vertex1]->setValue($value);
        }
    }
}
