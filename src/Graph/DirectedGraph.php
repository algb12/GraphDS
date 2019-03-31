<?php
namespace GraphDS\Graph;

use GraphDS\Vertex\DirectedVertex;
use GraphDS\Edge\DirectedEdge;
use InvalidArgumentException;

/**
 * Class defining a directed graph object.
 *
 * @property DirectedVertex[] $vertices
 * @property DirectedEdge[][] $edges
 */
class DirectedGraph extends Graph
{
    /**
     * Constructor for DirectedGraph object.
     */
    public function __construct()
    {
        $this->directed = true;
    }

    /**
     * Adds a directed vertex to the graph.
     *
     * @param string $vertex ID of the vertex
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
     * @param string $vertex ID of the vertex
     *
     * @throws InvalidArgumentException
     */
    public function removeVertex($vertex)
    {
        if (empty($this->vertices[$vertex])) {
            throw new InvalidArgumentException("Vertex $vertex does not exist.");
        }
        $neighbors = $this->vertices[$vertex]->getNeighbors();
        foreach ($neighbors['out'] as $neighbor) {
            if (($key = array_search($vertex, $this->vertices[$neighbor]->getInNeighbors())) !== false) {
                unset($this->vertices[$neighbor]->neighbors['in'][$key]);
            }
        }
        foreach ($neighbors['in'] as $neighbor) {
            if (($key = array_search($vertex, $this->vertices[$neighbor]->getOutNeighbors())) !== false) {
                unset($this->vertices[$neighbor]->neighbors['out'][$key]);
            }
            if ($this->edge($neighbor, $vertex)) {
                $this->removeEdge($neighbor, $vertex);
            }
        }
        unset($this->edges[$vertex], $this->vertices[$vertex]);
    }

    /**
     * Returns an edge object in the graph from $vertex1 to $vertex2.
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     *
     * @return DirectedEdge Instance of DirectedEdge from $vertex1 to $vertex2 and null if none exists
     *
     * @throws InvalidArgumentException
     */
    public function edge($vertex1, $vertex2)
    {
        if (empty($this->vertices[$vertex1]) || empty($this->vertices[$vertex2])) {
            throw new InvalidArgumentException('One of the vertices does not exist.');
        }
        if (isset($this->edges[$vertex1][$vertex2])) {
            return $this->edges[$vertex1][$vertex2];
        }

        return null;
    }

    /**
     * Adds a directed edge between two directed vertices ($vertex1 to $vertex2).
     *
     * @param string $vertex1 ID of first vertex
     * @param string $vertex2 ID of second vertex
     * @param float $value The value/weight the edge should hold
     *
     * @throws InvalidArgumentException
     */
    public function addEdge($vertex1, $vertex2, $value = null)
    {
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
     *
     * @throws InvalidArgumentException
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
     * Transposes the graph, reversing each directed edge.
     *
     * @return DirectedGraph The transposed graph
     *
     * @throws InvalidArgumentException
     */
    public function getTranspose()
    {
        $graph = clone $this;

        foreach ($graph->edges as $vertex1 => $vertex1Data) {
            foreach ($vertex1Data as $vertex2 => $vertex2Data) {
                $value = $graph->edge($vertex1, $vertex2)->getValue();
                $graph->removeEdge($vertex1, $vertex2);
                $graph->addEdge($vertex2, $vertex1, $value);
            }
        }

        return $graph;
    }
}
