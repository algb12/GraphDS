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
            ++$this->vertexCount;
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
            if ($this->edge($neighbor, $vertex)) {
                $this->removeEdge($neighbor, $vertex);
            }
        }
        unset($this->edges[$vertex]);
        unset($this->vertices[$vertex]);
        --$this->vertexCount;
    }

    /**
     * Returns an edge object in the graph, regardless of vertex order (undirected).
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     *
     * @return object Instance of UndirectedEdge between $vertex1 and $vertex2
     */
    public function edge($vertex1, $vertex2)
    {
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (isset($this->edges[$vertex1][$vertex2])) {
            return $this->edges[$vertex1][$vertex2];
        } elseif (isset($this->edges[$vertex2][$vertex1])) {
            return $this->edges[$vertex2][$vertex1];
        }
    }

    /**
     * Adds an undirected edge between two undirected vertices.
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     * @param double $value   The value/weight the edge should hold
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
            $this->edges[$vertex1][$vertex2] = new UndirectedEdge($vertex1, $vertex2, $value);
            $this->vertices[$vertex1]->addNeighbor($vertex2);
            $this->vertices[$vertex2]->addNeighbor($vertex1);
        }
        ++$this->edgeCount;
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
        if (null === $this->edge($vertex1, $vertex2)) {
            throw new InvalidArgumentException("No edge between $vertex1 and $vertex2.");
        }
        $this->vertices[$vertex1]->removeNeighbor($vertex2);
        $this->vertices[$vertex2]->removeNeighbor($vertex1);
        if (isset($this->edges[$vertex1][$vertex2])) {
            unset($this->edges[$vertex1][$vertex2]);
            if (empty($this->edges[$vertex1])) {
                unset($this->edges[$vertex1]);
            }
        }
        if (isset($this->edges[$vertex1][$vertex2])) {
            unset($this->edges[$vertex2][$vertex1]);
            if (empty($this->edges[$vertex2])) {
                unset($this->edges[$vertex2]);
            }
        }
        --$this->edgeCount;
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
        if (null === $this->edge($vertex1, $vertex2)) {
            throw new InvalidArgumentException("No edge between $vertex1 and $vertex2.");
        }

        return $this->edge($vertex1, $vertex2)->getValue();
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
        if (null === $this->edge($vertex1, $vertex2)) {
            throw new InvalidArgumentException("No edge between $vertex1 and $vertex2.");
        }
        $this->edge($vertex1, $vertex2)->setValue($value);
    }
}
