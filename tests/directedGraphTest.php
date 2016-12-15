<?php

use PHPUnit\Framework\TestCase;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Algo\Dijkstra;
use GraphDS\Algo\FloydWarshall;

class DirectedGraphTest extends TestCase
{
    public function testVertexAddRemove() {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $this->assertArrayHasKey('v1', $g->vertices);
        $g->addVertex('v2');
        $this->assertArrayHasKey('v2', $g->vertices);
        $g->addVertex('v3');
        $this->assertArrayHasKey('v3', $g->vertices);
        $g->addEdge('v1', 'v2');
        $this->assertArrayHasKey('v2', $g->edges['v1']);
        $g->addEdge('v1', 'v3');
        $this->assertArrayHasKey('v3', $g->edges['v1']);
        $this->assertContains('v3', $g->vertices['v1']->getOutNeighbors());
        $g->removeVertex('v3');
        $this->assertArrayNotHasKey('v3', $g->vertices);
        $this->assertArrayNotHasKey('v3', $g->edges['v1']);
        $this->assertNotContains('v3', $g->vertices['v1']->getOutNeighbors());
        $g->removeVertex('v2');
        $this->assertArrayNotHasKey('v2', $g->vertices);
        $this->assertArrayNotHasKey('v1', $g->edges);
    }

    public function testEdgeAddRemove() {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addEdge('v1', 'v2');
        $this->assertEquals(isset($g->edges['v1']['v2']), true);
        $this->assertEquals(isset($g->edges['v2']['v1']), false);
        $this->assertContains('v2', $g->vertices['v1']->getOutNeighbors());
        $this->assertContains('v1', $g->vertices['v2']->getInNeighbors());
        $this->assertNotContains('v2', $g->vertices['v1']->getInNeighbors());
        $this->assertNotContains('v1', $g->vertices['v2']->getOutNeighbors());
        $g->removeEdge('v1', 'v2');
        $this->assertEquals(isset($g->edges['v1']['v2']), false);
        $this->assertEquals(isset($g->edges['v2']['v1']), false);
        $this->assertNotContains('v2', $g->vertices['v1']->getOutNeighbors());
        $this->assertNotContains('v1', $g->vertices['v2']->getInNeighbors());
    }

    public function testVertexGetSetValue() {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->vertices['v1']->setValue('testval1');
        $this->assertEquals($g->vertices['v1']->getValue(), 'testval1');
        $g->addVertex('v2');
        $g->vertices['v2']->setValue('testval2');
        $this->assertEquals($g->vertices['v2']->getValue(), 'testval2');
    }

    public function testEdgeGetSetValue() {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v1');
        $g->edges['v1']['v2']->setValue('testval1');
        $g->edges['v1']['v3']->setValue('testval2');
        $g->edges['v3']['v1']->setValue('testval3');
        $this->assertEquals($g->edges['v1']['v2']->getValue(), 'testval1');
        $this->assertEquals($g->edges['v1']['v3']->getValue(), 'testval2');
        $this->assertEquals($g->edges['v3']['v1']->getValue(), 'testval3');
    }

    public function testVertexAdjacencyMethods() {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addVertex('v4');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v2');
        $this->assertEquals($g->vertices['v1']->outAdjacent('v2'), true);
        $this->assertEquals($g->vertices['v2']->outAdjacent('v1'), false);
        $this->assertEquals($g->vertices['v3']->inAdjacent('v1'), true);
        $this->assertEquals($g->vertices['v1']->inAdjacent('v3'), false);
        $this->assertEquals($g->vertices['v1']->adjacent('v2'), true);
        $this->assertEquals($g->vertices['v2']->adjacent('v1'), true);
        $this->assertEquals($g->vertices['v3']->adjacent('v1'), true);
        $this->assertEquals($g->vertices['v1']->adjacent('v3'), true);
        $this->assertEquals($g->vertices['v1']->adjacent('v4'), false);
        $this->assertEquals($g->vertices['v4']->adjacent('v1'), false);
    }

    public function testIndegreeAndOutdegree() {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v1');
        $this->assertEquals($g->vertices['v1']->getIndegree(), 1);
        $this->assertEquals($g->vertices['v1']->getOutdegree(), 2);
        $this->assertEquals($g->vertices['v2']->getIndegree(), 1);
        $this->assertEquals($g->vertices['v2']->getOutdegree(), 0);
        $this->assertEquals($g->vertices['v3']->getIndegree(), 1);
        $this->assertEquals($g->vertices['v3']->getOutdegree(), 1);
    }

    public function testDijkstra() {
        $g = new DirectedGraph;

        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addVertex('D');
        $g->addVertex('E');
        $g->addVertex('F');
        $g->addVertex('G');
        $g->addVertex('H');

        $g->addEdge('A', 'B', 20);
        $g->addEdge('A', 'D', 80);
        $g->addEdge('A', 'G', 90);
        $g->addEdge('B', 'F', 10);
        $g->addEdge('C', 'D', 10);
        $g->addEdge('C', 'F', 50);
        $g->addEdge('C', 'H', 20);
        $g->addEdge('D', 'C', 10);
        $g->addEdge('D', 'G', 20);
        $g->addEdge('E', 'B', 50);
        $g->addEdge('E', 'G', 30);
        $g->addEdge('F', 'C', 10);
        $g->addEdge('F', 'D', 40);
        $g->addEdge('G', 'A', 20);

        $d = new Dijkstra($g);

        $d->calcDijkstra('A');
        $res_E = $d->getPath('E');

        $this->assertEmpty($res_E['path']);
        $this->assertEquals($res_E['dist'], INF);

        $res_C = $d->getPath('C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($res_C['path'], $expected_path);
        $this->assertEquals($res_C['dist'], 40);

        $d->calcDijkstra('B');
        $res_A = $d->getPath('A');
        $expected_path = array('B', 'F', 'C', 'D', 'G', 'A');
        $this->assertEquals($res_A['path'], $expected_path);
        $this->assertEquals($res_A['dist'], 70);
    }

    public function testFloydWarshall() {
        $g = new DirectedGraph;

        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addVertex('D');
        $g->addVertex('E');
        $g->addVertex('F');
        $g->addVertex('G');
        $g->addVertex('H');

        $g->addEdge('A', 'B', 20);
        $g->addEdge('A', 'D', 80);
        $g->addEdge('A', 'G', 90);
        $g->addEdge('B', 'F', 10);
        $g->addEdge('C', 'D', 10);
        $g->addEdge('C', 'F', 50);
        $g->addEdge('C', 'H', 20);
        $g->addEdge('D', 'C', 10);
        $g->addEdge('D', 'G', 20);
        $g->addEdge('E', 'B', 50);
        $g->addEdge('E', 'G', 30);
        $g->addEdge('F', 'C', 10);
        $g->addEdge('F', 'D', 40);
        $g->addEdge('G', 'A', 20);

        $d = new FloydWarshall($g);

        $d->calcFloydWarshall();
        $res_E = $d->getPath('A', 'E');

        $this->assertEmpty($res_E['path']);
        $this->assertEquals($res_E['dist'], null);

        $res_C = $d->getPath('A', 'C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($res_C['path'], $expected_path);
        $this->assertEquals($res_C['dist'], 40);

        $res_A = $d->getPath('B', 'A');
        $expected_path = array('B', 'F', 'C', 'D', 'G', 'A');
        $this->assertEquals($res_A['path'], $expected_path);
        $this->assertEquals($res_A['dist'], 70);
    }
}
