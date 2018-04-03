<?php
/**
 * Breadth-first search.
 */
namespace GraphDS\Algo;

use GraphDS\Graph\Graph;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
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
        if (empty($graph) || !($graph instanceof Graph)) {
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
     * @param mixed $root ID of the vertex from which the BFS should begin
     */
    public function run($root)
    {
        foreach (array_keys($this->graph->vertices) as $vertex) {
            $this->dist[$vertex] = INF;
            $this->parent[$vertex] = null;
        }

        $this->discovered = array();

        $queue = new SplQueue();

        $this->dist[$root] = 0;
        $queue->enqueue($root);

        while (!$queue->isEmpty()) {
            $this->discovered[] = $current = $queue->dequeue();

            if ($this->graph instanceof UndirectedGraph) {
                $neighbors = $this->graph->vertices[$current]->getNeighbors();
            } elseif ($this->graph instanceof DirectedGraph) {
                $neighbors = $this->graph->vertices[$current]->getOutNeighbors();
            }

            foreach ($neighbors as $vertex) {
                if ($this->dist[$vertex] == INF) {
                    $this->dist[$vertex] = $this->dist[$current] + 1;
                    $this->parent[$vertex] = $current;
                    $queue->enqueue($vertex);
                }
            }
        }
    }

    /**
     * Returns the result of the BFS.
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
