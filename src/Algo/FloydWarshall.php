<?php
/**
 * Floyd-Warshall algorithm for finding the shortest path with path reconstruction.
 */
namespace GraphDS\Algo;

use InvalidArgumentException;

/**
 * Class defining the Floyd-Warshall algorithm.
 */
class FloydWarshall
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
     * Array holding the next vertices for each vertex for the shortest path.
     *
     * @var array
     */
    public $next = array();
    /**
     * Constructor for the Floyd-Warshall algorithm.
     *
     * @param object $graph The graph to which the Floyd-Warshall should be applied
     */
    public function __construct($graph)
    {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException('Floyd-Warshall shortest path algorithm requires a graph.');
        }
        $this->graph = &$graph;
    }

    /**
     * Calculates the shortest paths in the graph using the Floyd-Warshall algorithm.
     */
    public function calcFloydWarshall()
    {
        foreach ($this->graph->vertices as $u => &$uv) {
            foreach ($this->graph->vertices as $v => &$vv) {
                $this->dist[$u][$v] = INF;
            }
        }
        foreach ($this->graph->vertices as $u => &$uv) {
            foreach ($this->graph->vertices as $v => &$vv) {
                $this->next[$u][$v] = null;
            }
        }
        foreach ($this->graph->vertices as $v => &$vv) {
            $this->dist[$v][$v] = 0;
        }
        foreach ($this->graph->edges as $u => &$uv) {
            foreach ($uv as $v => &$vv) {
                $this->dist[$u][$v] = $vv->getValue();
                $this->next[$u][$v] = $v;
            }
        }
        foreach ($this->graph->vertices as $k => &$kv) {
            foreach ($this->graph->vertices as $i => &$iv) {
                foreach ($this->graph->vertices as $j => &$jv) {
                    if ($this->dist[$i][$j] > ($this->dist[$i][$k] + $this->dist[$k][$j])) {
                        $this->dist[$i][$j] = $this->dist[$i][$k] + $this->dist[$k][$j];
                        $this->next[$i][$j] = $this->next[$i][$k];
                    }
                }
            }
        }
    }

    /**
     * Returns the shortest path from vertex $start to $dest in the graph.
     *
     * @param string $start ID of the start vertex
     * @param string $dest  ID of the destination vertex
     *
     * @return array An array containing the shortest path and distance
     */
    public function getPath($start, $dest)
    {
        $s = $start;
        $path = array($start);
        while ($start !== $dest) {
            if ($start = $this->next[$start][$dest]) {
                $path[] = $start;
            } else {
                return;
            }
        }

        $result['path'] = $path;
        $result['dist'] = $this->dist[$s][$dest];

        return $result;
    }
}
