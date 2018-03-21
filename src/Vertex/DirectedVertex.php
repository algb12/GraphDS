<?php
/**
 * Directed vertex.
 */

namespace GraphDS\Vertex;

/**
 * Class defining a directed vertex object.
 */
class DirectedVertex extends Vertex
{
    /**
     * Constructor for DirectedVertex object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->neighbors['in'] = array();
        $this->neighbors['out'] = array();
    }

    /**
     * Adds a neighboring, incoming, directed vertex to this vertex.
     *
     * @param string $vertex ID of the vertex
     */
    public function addInNeighbor($vertex)
    {
        $this->neighbors['in'][] = $vertex;
    }

    /**
     * Removes a neighboring, incoming, directed vertex from this vertex.
     *
     * @param string $vertex ID of the vertex
     */
    public function removeInNeighbor($vertex)
    {
        if (($key = array_search($vertex, $this->neighbors['in'])) !== false) {
            unset($this->neighbors['in'][$key]);
        }
    }

    /**
     * Returns an array of all incoming neighbor vertices.
     *
     * @return array Array of all incoming neighbor vertices
     */
    public function getInNeighbors()
    {
        return $this->neighbors['in'];
    }

    /**
     * Adds a neighboring, outgoing, directed vertex to this vertex.
     *
     * @param string $vertex ID of the vertex
     */
    public function addOutNeighbor($vertex)
    {
        $this->neighbors['out'][] = $vertex;
    }

    /**
     * Removes a neighboring, outgoing, directed vertex from this vertex.
     *
     * @param string $vertex ID of the vertex
     */
    public function removeOutNeighbor($vertex)
    {
        if (($key = array_search($vertex, $this->neighbors['out'])) !== false) {
            unset($this->neighbors['out'][$key]);
        }
    }

    /**
     * Returns an array of all outgoing neighbor vertices.
     *
     * @return array Array of all outgoing neighbor vertices
     */
    public function getOutNeighbors()
    {
        return $this->neighbors['out'];
    }

    /**
     * Returns an array of all neighboring vertices.
     *
     * @return array Array of all neighboring vertices
     */
    public function getNeighbors()
    {
        return $this->neighbors;
    }

    /**
     * Returns the number of incoming neighbor vertices (indegree).
     *
     * @return int Number of incoming vertices
     */
    public function getIndegree()
    {
        return count($this->neighbors['in']);
    }

    /**
     * Returns the number of outgoing neighbor vertices (outdegree).
     *
     * @return int Number of outgoing vertices
     */
    public function getOutdegree()
    {
        return count($this->neighbors['out']);
    }

    /**
     * Checks if a given vertex is an incoming neighbor of this vertex.
     *
     * @param string $vertex ID of vertex
     *
     * @return bool Whether given vertex is an incoming neighbor of this vertex
     */
    public function inAdjacent($vertex)
    {
        return in_array($vertex, $this->neighbors['in']);
    }

    /**
     * Checks if a given vertex is an outgoing neighbor of this vertex.
     *
     * @param string $vertex ID of vertex
     *
     * @return bool Whether given vertex is an outgoing neighbor of this vertex
     */
    public function outAdjacent($vertex)
    {
        return in_array($vertex, $this->neighbors['out']);
    }

    /**
     * Checks if a given vertex is adjacent to this vertex.
     *
     * @param string $vertex ID of vertex
     *
     * @return bool Whether given vertex is adjacent to this vertex
     */
    public function adjacent($vertex)
    {
        return in_array($vertex, $this->neighbors['in']) || in_array($vertex, $this->neighbors['out']);
    }
}
