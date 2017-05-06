<?php
/**
 * Breadth-first search.
 */
namespace GraphDS\Algo;

use InvalidArgumentException;
use SplQueue;

/**
 * Class defining the breadth-dirst search.
 */
class BFS
{
    /**
     * Reference to the graph.
     *
     * @var object
     */
    public $graph;
    /**
     * Distances for each node to root in hops.
     *
     * @var array
     */
    public $dist;
    /**
     * Parents for each vertex.
     *
     * @var array
     */
    public $parent;
    /**
     * Discovered vertices, in BFS order.
     *
     * @var array
     */
    public $discovered;

    /**
     * Constructor for the BFS algorithm.
     *
     * @param object $graph The graph to which the BFS algorithm should be applied
     */
    public function __construct($graph)
    {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException("Dijkstra's shortest path algorithm requires a graph.");
        }
        $this->graph = &$graph;
        $this->dist = array();
        $this->parent = array();
        $this->discovered = array();
    }

    /**
     * Runs the BFS from a given vertex $vertex on the graph.
     *
     * @param mixed $vertex ID of the vertex from which the BFS should begin
     */
    public function run($root)
    {
        foreach (array_keys($this->graph->vertices) as $vertex) {
            $this->dist[$vertex] = INF;
            $this->parent[$vertex] = null;
        }

        $queue = new SplQueue();

        $this->dist[$root] = 0;
        $queue->enqueue($root);

        while (!$queue->isEmpty()) {
            $this->discovered[] = $current = $queue->dequeue();

            if (get_class($this->graph) === 'GraphDS\Graph\UndirectedGraph') {
                $neighbors = $this->graph->vertices[$current]->getNeighbors();
            } elseif (get_class($this->graph) === 'GraphDS\Graph\DirectedGraph') {
                $neighbors = $this->graph->vertices[$current]->getOutNeighbors();
            }

            foreach ($this->graph->vertices[$current]->getNeighbors() as $vertex) {
                if ($this->dist[$vertex] == INF) {
                    $this->dist[$vertex] = $this->dist[$current] + 1;
                    $this->parent[$vertex] = $current;
                    $queue->enqueue($vertex);
                }
            }
        }
    }

    /**
     * Returns the result of a BFS Returns.
     *
     * @return array Array of vertex distance to root, parents and vertices in BFS order
     */
    public function get()
    {
        return array(
            'dist' => $this->dist,
            'parent' => $this->parent,
            'discovered' => $this->discovered,
        );
    }
}
