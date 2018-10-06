<?php

namespace Tests\Algo;

use GraphDS\Graph\DirectedGraph;
use GraphDS\Algo\BFS;
use GraphDS\Graph\UndirectedGraph;
use PHPUnit\Framework\TestCase;
use Tests\Traits\GraphInteractionTrait;

class BFSTest extends TestCase
{
    use GraphInteractionTrait;

    /**
     * @var BFS
     */
    private $SUT;

    public function testTheBFSWithDirectedGraph()
    {
        $directedGraph = new DirectedGraph();

        $this->addVerticesAndEdgesForTraversalTests($directedGraph);

        $this->SUT = new BFS($directedGraph);

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

        $expected_discovered = array('A', 'B', 'C', 'D', 'E');
        $this->assertEquals($expected_discovered, $res_A['discovered']);

        $this->SUT->run('C');
        $res_C = $this->SUT->get();
        $expected_discovered = array('C', 'E');
        $this->assertEquals($expected_discovered, $res_C['discovered']);
    }

    public function testBFSWithUndirectedGraph()
    {
        $undirectedGraph = new UndirectedGraph();

        $this->addVerticesAndEdgesForTraversalTests($undirectedGraph);

        $d = new BFS($undirectedGraph);

        $d->run('A');
        $res_A = $d->get();

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

        $expected_discovered = array('A', 'B', 'C', 'D', 'E');
        $this->assertEquals($expected_discovered, $res_A['discovered']);
    }

}