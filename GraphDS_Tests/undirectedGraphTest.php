<?php

use PHPUnit\Framework\TestCase;
use GraphDS\Graph\UndirectedGraph;

class UndirectedGraphTest extends TestCase
{
    public function testVertexAddRemove() {
        $g = new UndirectedGraph();
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
        $this->assertContains('v3', $g->vertices['v1']->getNeighbors());
        $g->removeVertex('v3');
        $this->assertArrayNotHasKey('v3', $g->vertices);
        $this->assertArrayNotHasKey('v3', $g->edges['v1']);
        $this->assertNotContains('v3', $g->vertices['v1']->getNeighbors());
        $g->removeVertex('v2');
        $this->assertArrayNotHasKey('v2', $g->vertices);
        $this->assertArrayNotHasKey('v1', $g->edges);
    }

    public function testEdgeAddRemove() {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addEdge('v1', 'v2');
        $this->assertEquals(isset($g->edges['v1']['v2']), true);
        $this->assertEquals(isset($g->edges['v2']['v1']), true);
        $this->assertContains('v2', $g->vertices['v1']->getNeighbors());
        $this->assertContains('v1', $g->vertices['v2']->getNeighbors());
        $g->removeEdge('v1', 'v2');
        $this->assertEquals(isset($g->edges['v1']['v2']), false);
        $this->assertEquals(isset($g->edges['v2']['v1']), false);
        $this->assertNotContains('v2', $g->vertices['v1']->getNeighbors());
        $this->assertNotContains('v1', $g->vertices['v2']->getNeighbors());
    }

    public function testVertexGetSetValue() {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->vertices['v1']->setValue('testval1');
        $this->assertEquals($g->vertices['v1']->getValue(), 'testval1');
        $g->addVertex('v2');
        $g->vertices['v2']->setValue('testval2');
        $this->assertEquals($g->vertices['v2']->getValue(), 'testval2');
    }

    public function testEdgeGetSetValue() {
        $g = new UndirectedGraph();
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
        $this->assertEquals($g->edges['v1']['v3']->getValue(), 'testval3');
        $this->assertEquals($g->edges['v3']['v1']->getValue(), 'testval3');
    }

    public function testVertexAdjacencyMethods() {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addVertex('v4');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v2');
        $this->assertEquals($g->vertices['v1']->adjacent('v2'), true);
        $this->assertEquals($g->vertices['v2']->adjacent('v1'), true);
        $this->assertEquals($g->vertices['v3']->adjacent('v1'), true);
        $this->assertEquals($g->vertices['v1']->adjacent('v3'), true);
        $this->assertEquals($g->vertices['v1']->adjacent('v4'), false);
        $this->assertEquals($g->vertices['v4']->adjacent('v1'), false);
    }
}
