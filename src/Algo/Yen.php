<?php

namespace GraphDS\Algo;

use GraphDS\Graph\Graph;
use InvalidArgumentException;

class Yen
{
    // Reference to the graph
    public $graph;

    // Any global variables...
    private $results;

    // Constructor accepts a GraphDS graph and validates it
    public function __construct($graph)
    {
        if (empty($graph) || !($graph instanceof Graph)) {
            throw new InvalidArgumentException("Algorithm requires a graph.");
        }
        $this->graph = &$graph;
        $this->results = array();
    }

    public function run($start, $dest, $k = 3)
    {
        $a = array();

        // Determine the shortest path from the start to the dest.
        $dm = new Dijkstra($this->graph);
        $dm->run($start);
        $a[0] = $dm->get($dest);

        for ($kIterator = 1; $kIterator < $k; $kIterator++) {
            // Initialize the set to store the potential kth shortest path.
            $b = array();

            // The spur node ranges from the first node to the next to last node in the previous k-shortest path.
            for ($i = 0, $iMax = count($a[$kIterator - 1]['path']) - 2; $i <= $iMax; $i++) {
                // Deep clone the graph to make sure we do not mutate it.
                $graphClone = unserialize(serialize($this->graph));

                // Spur node is retrieved from the previous k-shortest path, k − 1.
                $spurNode = $a[$kIterator - 1]['path'][$i];
                // The sequence of nodes from the start to the spur node of the previous k-shortest path.
                $rootPath = array_slice($a[$kIterator - 1]['path'], 0, $i + 1);
                // The length of the sequence from the start to the spur node of the previous k-shortest path.
                $rootPathLength = 0;
                for ($rootPathIterator = 0, $rootPathIteratorMax = count($rootPath) - 2; $rootPathIterator <= $rootPathIteratorMax; $rootPathIterator++) {
                    $rootPathLength += $graphClone->edge($rootPath[$rootPathIterator], $rootPath[$rootPathIterator + 1])->getValue();
                }

                foreach ($a as $p) {
                    if ($rootPath === array_slice($p['path'], 0, $i + 1)) {
                        // Remove the links that are part of the previous shortest paths which share the same root path.
                        if (null !== $graphClone->edge($p['path'][$i], $p['path'][$i + 1])) {
                            $graphClone->removeEdge($p['path'][$i], $p['path'][$i + 1]);
                        }
                    }
                }

                foreach ($rootPath as $rootPathNode) {
                    if ($rootPathNode !== $spurNode) {
                        $graphClone->removeVertex($rootPathNode);
                    }
                }

                // Calculate the spur path from the spur node to the dest.
                $dm = new Dijkstra($graphClone);
                $dm->run($spurNode);
                $spurPath = $dm->get($dest);

                // Entire path is made up of the root path and spur path.
                $totalPath = array_merge(array_slice($rootPath, 0, -1), $spurPath['path']);

                // Add the potential k-shortest path to the heap.
                if (count($spurPath['path']) > 0) {
                    $b[] = array(
                        'path' => $totalPath,
                        'dist' => $rootPathLength + $spurPath['dist'],
                    );
                }
            }

            if (empty($b)) {
                // This handles the case of there being no spur paths, or no spur paths left.
                // This could happen if the spur paths have already been exhausted (added to A),
                // or there are no spur paths at all - such as when both the start and dest vertices
                // lie along a "dead end".
                break;
            }

            // Sort the potential k-shortest paths by cost.
            usort($b, function ($first, $second) {
                return $first['dist'] - $second['dist'];
            });

            // Add the lowest cost path becomes the k-shortest path.
            $a[$kIterator] = $b[0];
        }

        $this->results = $a;
    }

    public function get()
    {
        return $this->results;
    }
}
