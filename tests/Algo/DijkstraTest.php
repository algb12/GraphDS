<?php

namespace Tests\Algo;

use GraphDS\Algo\Dijkstra;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use PHPUnit\Framework\TestCase;
use Tests\Traits\GraphInteractionTrait;

class DijkstraTest extends TestCase
{
    use GraphInteractionTrait;

    /**
     * @var Dijkstra
     */
    private $SUT;

    public function testDijkstraWithDirectedGraph()
    {
        $directedGraph = new DirectedGraph();

        $this->addVerticesAndEdgesForShortestPathTests($directedGraph);

        $this->SUT = new Dijkstra($directedGraph);

        $this->SUT->run('A');
        $res_E = $this->SUT->get('E');

        $this->assertEmpty($res_E['path']);
        $this->assertEquals(INF, $res_E['dist']);

        $res_C = $this->SUT->get('C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(40, $res_C['dist']);

        $this->SUT->run('B');
        $res_A = $this->SUT->get('A');
        $expected_path = array('B', 'F', 'C', 'D', 'G', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(70, $res_A['dist']);
    }

    public function testDijkstraWithUndirectedGraph()
    {
        $undirectedGraph = new UndirectedGraph();

        $this->addVerticesAndEdgesForShortestPathTests($undirectedGraph);

        $this->SUT = new Dijkstra($undirectedGraph);

        $this->SUT->run('A');
        $res_E = $this->SUT->get('E');

        $this->assertNotEmpty($res_E['path']);
        $expected_path = array('A', 'B', 'E');
        $this->assertEquals($expected_path, $res_E['path']);
        $this->assertEquals(70, $res_E['dist']);

        $res_C = $this->SUT->get('C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(80, $res_C['dist']);

        $this->SUT->run('B');
        $res_A = $this->SUT->get('A');
        $expected_path = array('B', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(20, $res_A['dist']);
    }
}