<?php

namespace Tests\Algo;

use GraphDS\Algo\DFS;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use PHPUnit\Framework\TestCase;
use Tests\Traits\GraphInteractionTrait;

class DFSTest extends TestCase
{
    use GraphInteractionTrait;

    /**
     * @var DFS
     */
    private $SUT;

    public function testDFSWithDirectedGraph()
    {
        $directedGraph = new DirectedGraph();
        $this->addVerticesAndEdgesForTraversalTests($directedGraph);

        $this->SUT = new DFS($directedGraph);

        $this->SUT->run('A');
        $res_A = $this->SUT->get();

        $this->assertEquals(0, $res_A['dist']['A']);
        $this->assertEquals(1, $res_A['dist']['B']);
        $this->assertEquals(1, $res_A['dist']['C']);
        $this->assertEquals(2, $res_A['dist']['D']);
        $this->assertEquals(2, $res_A['dist']['E']);

        $this->assertEquals(null, $res_A['parent']['A']);
        $this->assertEquals('A', $res_A['parent']['B']);
        $this->assertEquals('A', $res_A['parent']['C']);
        $this->assertEquals('B', $res_A['parent']['D']);
        $this->assertEquals('C', $res_A['parent']['E']);

        $expected_discovered = array('A', 'C', 'E', 'B', 'D');
        $this->assertEquals($expected_discovered, $res_A['discovered']);

        $this->SUT->run('C');
        $res_C = $this->SUT->get();
        $expected_discovered = array('C', 'E');
        $this->assertEquals($expected_discovered, $res_C['discovered']);
    }

    public function testDFSWithUndirectedGraph()
    {
        $undirectedGraph = new UndirectedGraph();
        $this->addVerticesAndEdgesForTraversalTests($undirectedGraph);

        $this->SUT = new DFS($undirectedGraph);

        $this->SUT->run('A');
        $res_A = $this->SUT->get();

        $this->assertEquals(0, $res_A['dist']['A']);
        $this->assertEquals(1, $res_A['dist']['B']);
        $this->assertEquals(1, $res_A['dist']['C']);
        $this->assertEquals(2, $res_A['dist']['D']);
        $this->assertEquals(2, $res_A['dist']['E']);

        $this->assertEquals(null, $res_A['parent']['A']);
        $this->assertEquals('A', $res_A['parent']['B']);
        $this->assertEquals('A', $res_A['parent']['C']);
        $this->assertEquals('B', $res_A['parent']['D']);
        $this->assertEquals('C', $res_A['parent']['E']);

        $expected_discovered = array('A', 'C', 'E', 'B', 'D');
        $this->assertEquals($expected_discovered, $res_A['discovered']);
    }


}