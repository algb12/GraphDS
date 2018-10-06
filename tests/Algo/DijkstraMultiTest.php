<?php

namespace Tests\Algo;

use GraphDS\Algo\DijkstraMulti;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\Graph;
use GraphDS\Graph\UndirectedGraph;
use PHPUnit\Framework\TestCase;

class DijkstraMultiTest extends TestCase
{
    /**
     * @var DijkstraMulti
     */
    private $SUT;

    public function testDijkstraMultiWithDirectedGraph()
    {
        $directedGraph = new DirectedGraph();
        $this->addEdgesAndVertices($directedGraph);

        $this->SUT = new DijkstraMulti($directedGraph);

        $this->SUT->run('A');
        $res_J = $this->SUT->get('J');

        $this->assertNotEmpty($res_J['paths']);
        $expected_paths = array(
            array('A', 'B', 'J'),
            array('A', 'C', 'E', 'F', 'J'),
            array('A', 'C', 'D', 'F', 'J'),
            array('A', 'G', 'H', 'I', 'J')
        );

        $this->assertEquals($expected_paths, $res_J['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(10, $res_J['dist']);

        $this->SUT->run('C');
        $res_J = $this->SUT->get('J');
        $expected_paths = array(
            array('C', 'D', 'F', 'J'),
            array('C', 'E', 'F', 'J')
        );
        $this->assertEquals($expected_paths, $res_J['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(7, $res_J['dist']);

        $this->SUT->run('J');
        $res_C = $this->SUT->get('C');

        $this->assertEmpty($res_C['paths']);
        $this->assertEquals(INF, $res_C['dist']);

    }

    public function testDijkstraMultiWithUndirectedGraph()
    {
        $undirectedGraph = new UndirectedGraph();

        $this->addEdgesAndVertices($undirectedGraph);

        $this->SUT = new DijkstraMulti($undirectedGraph);

        $this->SUT->run('A');
        $res_J = $this->SUT->get('J');

        $this->assertNotEmpty($res_J['paths']);
        $expected_paths = array(
            array('A', 'B', 'J'),
            array('A', 'C', 'E', 'F', 'J'),
            array('A', 'C', 'D', 'F', 'J'),
            array('A', 'G', 'H', 'I', 'J')
        );
        $this->assertEquals($expected_paths, $res_J['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(10, $res_J['dist']);

        $this->SUT->run('C');
        $res_J = $this->SUT->get('J');
        $expected_paths = array(
            array('C', 'D', 'F', 'J'),
            array('C', 'E', 'F', 'J')
        );
        $this->assertEquals($expected_paths, $res_J['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(7, $res_J['dist']);

        $this->SUT->run('J');
        $res_C = $this->SUT->get('C');
        $expected_paths = array(
            array('J', 'F', 'D', 'C'),
            array('J', 'F', 'E', 'C')
        );
        $this->assertEquals($expected_paths, $res_C['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(7, $res_C['dist']);
    }

    /**
     * @param Graph|DirectedGraph|UndirectedGraph $graph
     */
    private function addEdgesAndVertices(Graph $graph)
    {
        $graph->addVertex('A');
        $graph->addVertex('B');
        $graph->addVertex('C');
        $graph->addVertex('D');
        $graph->addVertex('E');
        $graph->addVertex('F');
        $graph->addVertex('G');
        $graph->addVertex('H');
        $graph->addVertex('I');
        $graph->addVertex('J');
        $graph->addVertex('K');

        $graph->addEdge('A', 'B', 5);
        $graph->addEdge('A', 'C', 3);
        $graph->addEdge('A', 'G', 3);
        $graph->addEdge('B', 'F', 3);
        $graph->addEdge('B', 'J', 5);
        $graph->addEdge('B', 'K', 2);
        $graph->addEdge('C', 'B', 3);
        $graph->addEdge('C', 'D', 2);
        $graph->addEdge('C', 'E', 1);
        $graph->addEdge('D', 'F', 2);
        $graph->addEdge('E', 'F', 3);
        $graph->addEdge('F', 'I', 2);
        $graph->addEdge('F', 'J', 3);
        $graph->addEdge('G', 'E', 4);
        $graph->addEdge('G', 'H', 3);
        $graph->addEdge('H', 'I', 2);
        $graph->addEdge('I', 'J', 2);
        $graph->addEdge('I', 'K', 3);
        $graph->addEdge('J', 'K', 7);
    }
}