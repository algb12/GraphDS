<?php
/**
 * Dijkstra's shortest path algorithm
 */

namespace GraphDS\Algo;

use InvalidArgumentException;

/**
 * Class defining Dijkstra's shortest path algorithm
 */
class Dijkstra
{
    /**
     * Reference to the graph
     * @var object
     */
    public $graph;
    /**
     * Array holding the shortest distances to each vertex from the source
     * @var array
     */
    public $dist = array();
    /**
     * Array holding the previous vertices for each vertex for the shortest path
     * @var array
     */
    public $prev = array();
    /**
     * Array holding the unvisited verticess in the graph
     * @var array
     */
    public $unvisited_vertices = array();

    /**
     * Constructor for the Dijkstra algorithm
     * @param object $graph The graph to which the Dijkstra's algorithm should be applied
     */
    public function __construct($graph) {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException("Dijkstra's shortest path algorithm requires a graph.");
        }
        $this->graph =& $graph;
    }

    /**
     * Calculates the shortest path to every vertex from vertex $start
     * @param  mixed $start The starting vertex for Dijkstra's algorithm
     * @param  array $d     The ending vertex/vertices for Dijkstra's algorithm (only accepting array)
     * @return array        Array holding the distances and previous vertices as calculated by Dijkstra's algorithm
     */
    public function calcDijkstra($start) {
        if (empty($this->graph->vertices[$start])) {
            throw new InvalidArgumentException("Vertex $start does not exist.");
        }
        foreach ($this->graph->vertices as $k=>$v) {
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
            } else if (get_class($this->graph) === 'GraphDS\Graph\DirectedGraph') {
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

        foreach ($this->graph->vertices as $k=>$v) {
            $dest = $k;
            $path = array();
            while (isset($this->prev[$k])) {
                array_unshift($path, $k);
                $k = $this->prev[$k];
            }
            if ($k === $start) {
                array_unshift($path, $k);
            }
            $paths['path'][$dest] = $path;
            $paths['dist'][$dest] = $this->dist[$dest];
        }

        $result = $paths;
        return $result;
    }
}
