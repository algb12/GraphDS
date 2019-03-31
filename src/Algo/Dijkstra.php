<?php
/**
 * Dijkstra's shortest path algorithm.
 */
namespace GraphDS\Algo;

use GraphDS\Graph\Graph;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use InvalidArgumentException;

/**
 * Class defining Dijkstra's shortest path algorithm.
 */
class Dijkstra
{
    /**
     * Reference to the graph.
     *
     * @var Graph
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
    public $unvisitedVertices = array();

    /**
     * ID of the start vertex.
     *
     * @var array
     */
    public $start;

    /**
     * Constructor for the Dijkstra algorithm.
     *
     * @param Graph $graph The graph to which the Dijkstra algorithm should be applied
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Graph $graph)
    {
        if (!($graph instanceof DirectedGraph) && !($graph instanceof UndirectedGraph)) {
            throw new InvalidArgumentException(
                "Dijkstra's shortest path algorithm requires a directed or undirected graph"
            );
        }

        $this->graph = &$graph;
    }

    /**
     * Calculates the shortest path to every vertex from vertex $start.
     *
     * @param mixed $start ID of the starting vertex for Dijkstra's algorithm
     *
     * @throws InvalidArgumentException
     */
    public function run($start)
    {
        $this->start = $start;
        if (empty($this->graph->vertices[$start])) {
            throw new InvalidArgumentException("Vertex $start does not exist.");
        }
        foreach (array_keys($this->graph->vertices) as $vertex) {
            $this->dist[$vertex] = INF;
            $this->prev[$vertex] = null;
            $this->unvisitedVertices[$vertex] = null;
        }

        $this->dist[$start] = 0;

        while (count($this->unvisitedVertices) > 0) {
            $distUnvisited = array_intersect_key($this->dist, $this->unvisitedVertices);
            $minVertexTmp = array_keys($distUnvisited, min($distUnvisited));
            $minVertex = $minVertexTmp[0];
            unset($this->unvisitedVertices[$minVertex]);

            if ($this->graph instanceof UndirectedGraph) {
                $neighbors = $this->graph->vertices[$minVertex]->getNeighbors();
            } elseif ($this->graph instanceof DirectedGraph) {
                $neighbors = $this->graph->vertices[$minVertex]->getOutNeighbors();
            } else {
                throw new InvalidArgumentException("The provided graph is neither an undirected graph or a directed graph");
            }

            foreach ($neighbors as $vertex) {
                $alt = $this->dist[$minVertex] + $this->graph->edge($minVertex, $vertex)->getValue();
                if ($alt < $this->dist[$vertex]) {
                    $this->dist[$vertex] = $alt;
                    $this->prev[$vertex] = $minVertex;
                }
            }
        }
    }

    /**
     * Returns the shortest path to $destinationVertex from the origin vertex in the graph.
     *
     * @param  mixed $destinationVertex ID of the destination vertex
     *
     * @return array An array containing the shortest path and distance
     */
    public function get($destinationVertex)
    {
        $originalDestinationVertex = $destinationVertex;

        $path = array();

        while (isset($this->prev[$destinationVertex])) {
            array_unshift($path, $destinationVertex);
            $destinationVertex = $this->prev[$destinationVertex];
        }

        if ($destinationVertex === $this->start) {
            array_unshift($path, $destinationVertex);
        }

        return array(
            'path' => $path,
            'dist' => $this->dist[$originalDestinationVertex],
        );
    }
}
