<?php
/**
 * Multi-path version of Dijkstra's shortest path algorithm.
 */
namespace GraphDS\Algo;

use GraphDS\Graph\Graph;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use InvalidArgumentException;

/**
 * Class defining the multi-path version of Dijkstra's shortest path algorithm.
 */
class DijkstraMulti
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
     * @var mixed
     */
    public $start;
    /**
     * Array holding a single shortest path.
     *
     * @var array
     */
    public $path = array();
    /**
     * Array holding the shortest paths in the graph.
     *
     * @var array
     */
    public $paths = array();

    /**
     * Constructor for the multi-path version of Dijkstra algorithm.
     *
     * @param Graph $graph The graph to which the multi-path Dijkstra algorithm should be applied
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
     * @param mixed $start ID of the starting vertex for multi-path Dijkstra's algorithm
     *
     * @return array Array holding the distances and previous vertices as calculated by Dijkstra's algorithm
     * @throws \InvalidArgumentException
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
            }

            foreach ($neighbors as $vertex) {
                $alt = $this->dist[$minVertex] + $this->graph->edge($minVertex, $vertex)->getValue();
                if ($alt < $this->dist[$vertex]) {
                    $this->dist[$vertex] = $alt;
                    $this->prev[$vertex] = null;
                    $this->prev[$vertex][] = $minVertex;
                } elseif ($alt === $this->dist[$vertex]) {
                    $this->prev[$vertex][] = $minVertex;
                }
            }
        }

        return $this->prev;
    }

    /**
     * Returns all shortest paths to $dest from the origin vertex $this->start in the graph.
     *
     * @param string $dest ID of the destination vertex
     *
     * @return array An array containing the shortest path and distance
     */
    public function get($dest)
    {
        $this->paths = array();
        $this->enumerate($dest, $this->start);

        return array(
            'paths' => $this->paths,
            'dist' => $this->dist[$dest],
        );
    }

    /**
     * Enumerates the result of the multi-path Dijkstra as paths.
     *
     * @param string $source ID of the source vertex
     * @param string $dest   ID of the destination vertex
     */
    private function enumerate($source, $dest)
    {
        array_unshift($this->path, $source);
        $discovered[] = $source;

        if ($source === $dest) {
            $this->paths[] = $this->path;
        } else {
            if (!$this->prev[$source]) {
                return;
            }
            foreach ($this->prev[$source] as $child) {
                if (!in_array($child, $discovered)) {
                    $this->enumerate($child, $dest);
                }
            }
        }

        array_shift($this->path);
        if (($key = array_search($source, $discovered)) !== false) {
            unset($discovered[$key]);
        }
    }
}
