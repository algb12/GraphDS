<?php

namespace Tests\Algo;

use GraphDS\Algo\FloydWarshall;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use PHPUnit\Framework\TestCase;
use Tests\Traits\GraphInteractionTrait;

class FloydWarshallTest extends TestCase
{
    use GraphInteractionTrait;

    /**
     * @var FloydWarshall
     */
    private $SUT;

    public function testFloydWarshallWithDirectedGraph()
    {
        $directedGraph = new DirectedGraph();

        $this->addVerticesAndEdgesForShortestPathTests($directedGraph);

        $this->SUT = new FloydWarshall($directedGraph);

        $this->SUT->run();
        $res_E = $this->SUT->get('A', 'E');

        $this->assertEmpty($res_E['path']);
        $this->assertEquals(null, $res_E['dist']);

        $res_C = $this->SUT->get('A', 'C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(40, $res_C['dist']);

        $res_A = $this->SUT->get('B', 'A');
        $expected_path = array('B', 'F', 'C', 'D', 'G', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(70, $res_A['dist']);
    }

    public function testFloydWarshallWithUndirectedGraph()
    {
        $undirectedGraph = new UndirectedGraph();

        $this->addVerticesAndEdgesForShortestPathTests($undirectedGraph);

        $this->SUT = new FloydWarshall($undirectedGraph);

        $this->SUT->run();
        $res_E = $this->SUT->get('A', 'E');

        $this->assertNotEmpty($res_E['path']);
        $expected_path = array('A', 'B', 'E');
        $this->assertEquals($expected_path, $res_E['path']);
        $this->assertEquals(70, $res_E['dist']);

        $res_C = $this->SUT->get('A', 'C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(80, $res_C['dist']);

        $res_A = $this->SUT->get('B', 'A');
        $expected_path = array('B', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(20, $res_A['dist']);
    }
}