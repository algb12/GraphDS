<?php

namespace Tests\Traits;

use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\Graph;
use GraphDS\Graph\UndirectedGraph;

trait GraphInteractionTrait
{
    /**
     * @param DirectedGraph|UndirectedGraph|Graph $graph
     */
    protected function addVerticesAndEdgesForTraversalTests(Graph $graph)
    {
        $graph->addVertex('A');
        $graph->addVertex('B');
        $graph->addVertex('C');
        $graph->addVertex('D');
        $graph->addVertex('E');

        $graph->addEdge('A', 'B', 1);
        $graph->addEdge('A', 'C', 1);
        $graph->addEdge('B', 'D', 1);
        $graph->addEdge('C', 'E', 1);
    }

    /**
     * @param UndirectedGraph|DirectedGraph|Graph $graph
     */
    protected function addVerticesAndEdgesForShortestPathTests(Graph $graph)
    {
        $graph->addVertex('A');
        $graph->addVertex('B');
        $graph->addVertex('C');
        $graph->addVertex('D');
        $graph->addVertex('E');
        $graph->addVertex('F');
        $graph->addVertex('G');
        $graph->addVertex('H');

        $graph->addEdge('A', 'B', 20);
        $graph->addEdge('A', 'D', 80);
        $graph->addEdge('A', 'G', 90);
        $graph->addEdge('B', 'F', 10);
        $graph->addEdge('C', 'D', 10);
        $graph->addEdge('C', 'F', 50);
        $graph->addEdge('C', 'H', 20);
        $graph->addEdge('D', 'C', 10);
        $graph->addEdge('D', 'G', 20);
        $graph->addEdge('E', 'B', 50);
        $graph->addEdge('E', 'G', 30);
        $graph->addEdge('F', 'C', 10);
        $graph->addEdge('F', 'D', 40);
        $graph->addEdge('G', 'A', 20);
    }
}
