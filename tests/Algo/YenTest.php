<?php

namespace Tests\Algo;

use GraphDS\Algo\Yen;
use GraphDS\Graph\Graph;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use PHPUnit\Framework\TestCase;
use Tests\Traits\GraphInteractionTrait;

class YenTest extends TestCase
{
    use GraphInteractionTrait;

    /**
     * @var Yen
     */
    private $SUT;

    public function testYenWithDirectedGraph()
    {
        $directedGraph = new DirectedGraph();
        $this->addEdgesAndVertices($directedGraph);

        $this->SUT = new Yen($directedGraph);

        $this->SUT->run('C', 'H', '3');
        $result = $this->SUT->get();

        $expected = array(
            array(
                'path' => array('C', 'E', 'F', 'H'),
                'dist' => 5,
            ),
            array(
                'path' => array('C', 'E', 'G', 'H'),
                'dist' => 7,
            ),
            array(
                'path' => array('C', 'D', 'F', 'H'),
                'dist' => 8,
            ),
        );
        $this->assertEquals($expected, $result);
    }

    public function testYenWithUndirectedGraph()
    {
        $undirectedGraph = new UndirectedGraph();

        $this->addEdgesAndVertices($undirectedGraph);

        $this->SUT = new Yen($undirectedGraph);

        $this->SUT->run('C', 'H', '3');
        $result = $this->SUT->get();

        $expected = array(
            array(
                'path' => array('C', 'E', 'F', 'H'),
                'dist' => 5,
            ),
            array(
                'path' => array('C', 'D', 'E', 'F', 'H'),
                'dist' => 7,
            ),
            array(
                'path' => array('C', 'D', 'F', 'H'),
                'dist' => 8,
            ),
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * @param Graph|DirectedGraph|UndirectedGraph $graph
     */
    private function addEdgesAndVertices(Graph $graph)
    {
        $graph->addVertex('C');
        $graph->addVertex('D');
        $graph->addVertex('E');
        $graph->addVertex('F');
        $graph->addVertex('G');
        $graph->addVertex('H');

        $graph->addEdge('C', 'D', 3);
        $graph->addEdge('C', 'E', 2);
        $graph->addEdge('E', 'D', 1);
        $graph->addEdge('D', 'F', 4);
        $graph->addEdge('E', 'F', 2);
        $graph->addEdge('E', 'G', 3);
        $graph->addEdge('F', 'G', 2);
        $graph->addEdge('F', 'H', 1);
        $graph->addEdge('G', 'H', 2);
    }
}
