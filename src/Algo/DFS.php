<?php
/**
 * Depth-first search.
 */
namespace GraphDS\Algo;

use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use InvalidArgumentException;
use SplStack;

/**
 * Class defining depth-first search.
 */
class DFS
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
     * Discovered vertices, in DFS order.
     *
     * @var array
     */
    public $discovered;

    /**
     * Constructor for the DFS algorithm.
     *
     * @param object $graph The graph to which the DFS algorithm should be applied
     * @throws \InvalidArgumentException
     */
    public function __construct($graph)
    {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException("Dijkstra's shortest path algorithm requires a graph.");
        }
        $this->graph = &$graph;
        $this->discovered = array();
    }

    /**
     * Runs the DFS from a given vertex $vertex on the graph.
     *
     * @param mixed $root ID of the vertex from which the DFS should begin
     */
    public function run($root)
    {
        foreach (array_keys($this->graph->vertices) as $vertex) {
            $this->dist[$vertex] = INF;
            $this->parent[$vertex] = null;
        }

        $this->discovered = array();

        $stack = new SplStack();

        $this->dist[$root] = 0;
        $stack->push($root);

        while (!$stack->isEmpty()) {
            $current = $stack->pop();
            if (!in_array($current, $this->discovered)) {
                $this->discovered[] = $current;

                if ($this->graph instanceof UndirectedGraph) {
                    $neighbors = $this->graph->vertices[$current]->getNeighbors();
                } elseif ($this->graph instanceof DirectedGraph) {
                    $neighbors = $this->graph->vertices[$current]->getOutNeighbors();
                }

                foreach ($neighbors as $vertex) {
                    if ($this->dist[$vertex] == INF) {
                        $this->dist[$vertex] = $this->dist[$current] + 1;
                        $this->parent[$vertex] = $current;
                        $stack->push($vertex);
                    }
                }
            }
        }
    }

    /**
     * Returns the result of the DFS.
     *
     * @return array Array of vertex distance to root, parents and vertices in DFS order
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
