<?php
/**
 * Undirected vertex.
 */
namespace GraphDS\Vertex;

/**
 * Class defining an undirected vertex object.
 */
class UndirectedVertex extends Vertex
{
    /**
     * Adds a neighboring, undirected vertex to this vertex.
     *
     * @param string $vertex ID of vertex
     */
    public function addNeighbor($vertex)
    {
        $this->neighbors[] = $vertex;
    }

    /**
     * Removes a neighboring, undirected vertex from this vertex.
     *
     * @param string $vertex ID of vertex
     */
    public function removeNeighbor($vertex)
    {
        if (($key = array_search($vertex, $this->neighbors)) !== false) {
            unset($this->neighbors[$key]);
        }
    }

    /**
     * Returns an array of all neighboring vertices of this vertex.
     *
     * @return string[] Array of all neighboring vertices of this vertex
     */
    public function getNeighbors()
    {
        return $this->neighbors;
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
        return in_array($vertex, $this->neighbors);
    }
}
