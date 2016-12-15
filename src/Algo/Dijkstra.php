<?php
/**
 * Dijkstra's shortest path algorithm.
 */
namespace GraphDS\Algo;

use InvalidArgumentException;

/**
 * Class defining Dijkstra's shortest path algorithm.
 */
class Dijkstra
{
    /**
     * Reference to the graph.
     *
     * @var object
     */
    public $graph;
    /**
     * Array holding the shortest distances to each vertex from the source.
     *
     * @var array
     */
    public $dist = array();
    /**
     * Array holding the previous vertices for each vertex for the shortest path.
     *
     * @var array
     */
    public $prev = array();
    /**
     * Array holding the unvisited vertices in the graph.
     *
     * @var array
     */
    public $unvisited_vertices = array();
    /**
     * ID of the start vertex.
     *
     * @var array
     */
    public $start;

    /**
     * Constructor for the Dijkstra algorithm.
     *
     * @param object $graph The graph to which the Dijkstra algorithm should be applied
     */
    public function __construct($graph)
    {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException("Dijkstra's shortest path algorithm requires a graph.");
        }
        $this->graph = &$graph;
    }

    /**
     * Calculates the shortest path to every vertex from vertex $start.
     *
     * @param mixed $start ID of the starting vertex for Dijkstra's algorithm
     *
     * @return array Array holding the distances and previous vertices as calculated by Dijkstra's algorithm
     */
    public function calcDijkstra($start)
    {
        $this->start = $start;
        if (empty($this->graph->vertices[$start])) {
            throw new InvalidArgumentException("Vertex $start does not exist.");
        }
        foreach ($this->graph->vertices as $k => $v) {
            $this->dist[$k] = INF;
            $this->prev[$k] = null;
            $this->unvisited_vertices[$k] = null;
        }

        $this->dist[$start] = 0;

        while (count($this->unvisited_vertices) > 0) {
            $dist_unvisited = array_intersect_key($this->dist, $this->unvisited_vertices);
            $min_vertex = array_keys($dist_unvisited, min($dist_unvisited))[0];
            unset($this->unvisited_vertices[$min_vertex]);

            if (get_class($this->graph) === 'GraphDS\Graph\UndirectedGraph') {
                $neighbors = $this->graph->vertices[$min_vertex]->getNeighbors();
            } elseif (get_class($this->graph) === 'GraphDS\Graph\DirectedGraph') {
                $neighbors = $this->graph->vertices[$min_vertex]->getOutNeighbors();
            }

            foreach ($neighbors as $v) {
                $alt = $this->dist[$min_vertex] + $this->graph->edges[$min_vertex][$v]->getValue();
                if ($alt < $this->dist[$v]) {
                    $this->dist[$v] = $alt;
                    $this->prev[$v] = $min_vertex;
                }
            }
        }
    }

    /**
     * Returns the shortest path to $dest from the origin vertex in the graph.
     *
     * @param string $dest ID of the destination vertex
     *
     * @return array An array containing the shortest path and distance
     */
    public function getPath($dest)
    {
        $d = $dest;
        $path = array();
        while (isset($this->prev[$dest])) {
            array_unshift($path, $dest);
            $dest = $this->prev[$dest];
        }
        if ($dest === $this->start) {
            array_unshift($path, $dest);
        }
        $result['path'] = $path;
        $result['dist'] = $this->dist[$d];

        return $result;
    }
}
