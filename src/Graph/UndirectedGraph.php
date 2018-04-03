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
     * @param string $vertex ID of the vertex
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
     * @throws \InvalidArgumentException
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
        unset($this->edges[$vertex], $this->vertices[$vertex]);
    }

    /**
     * Returns an edge object in the graph, regardless of vertex order (undirected).
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     *
     * @return object Instance of UndirectedEdge between $vertex1 and $vertex2
     * @throws \InvalidArgumentException
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
     * @param float $value The value/weight the edge should hold
     * @throws \InvalidArgumentException
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
    }

    /**
     * Removes an undirected edge between two undirected vertices.
     *
     * @param string $vertex1 ID of first undirected vertex
     * @param string $vertex2 ID of second undirected vertex
     * @throws \InvalidArgumentException
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
        if (isset($this->edges[$vertex2][$vertex1])) {
            unset($this->edges[$vertex2][$vertex1]);
            if (empty($this->edges[$vertex2])) {
                unset($this->edges[$vertex2]);
            }
        }
    }
}
