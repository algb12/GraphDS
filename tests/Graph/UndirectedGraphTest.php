<?php

namespace Tests\Graph;

use GraphDS\Graph\UndirectedGraph;
use PHPUnit\Framework\TestCase;

class UndirectedGraphTest extends TestCase
{
    /**
     * @var UndirectedGraph
     */
    private $SUT;

    public function setUp()
    {
        parent::setUp();

        $this->SUT = new UndirectedGraph();
    }

    public function testVertexAddRemove()
    {
        $this->SUT->addVertex('A');
        $this->assertArrayHasKey('A', $this->SUT->vertices);
        $this->SUT->addVertex('B');
        $this->assertArrayHasKey('B', $this->SUT->vertices);
        $this->SUT->addVertex('C');
        $this->assertArrayHasKey('C', $this->SUT->vertices);
        $this->SUT->addEdge('A', 'B');
        $this->assertArrayHasKey('B', $this->SUT->edges['A']);
        $this->SUT->addEdge('A', 'C');
        $this->assertArrayHasKey('C', $this->SUT->edges['A']);
        $this->assertContains('C', $this->SUT->vertices['A']->getNeighbors());
        $this->SUT->removeVertex('C');
        $this->assertArrayNotHasKey('C', $this->SUT->vertices);
        $this->assertArrayNotHasKey('C', $this->SUT->edges['A']);
        $this->assertNotContains('C', $this->SUT->vertices['A']->getNeighbors());
        $this->SUT->removeVertex('B');
        $this->assertArrayNotHasKey('B', $this->SUT->vertices);
        $this->assertArrayNotHasKey('A', $this->SUT->edges);
    }

    public function testEdgeAddRemove()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addEdge('A', 'B');
        $this->assertNotNull($this->SUT->edge('A', 'B'));
        $this->assertNotNull($this->SUT->edge('B', 'A'));
        $this->assertContains('B', $this->SUT->vertices['A']->getNeighbors());
        $this->assertContains('A', $this->SUT->vertices['B']->getNeighbors());
        $this->SUT->removeEdge('A', 'B');
        $this->assertNull($this->SUT->edge('A', 'B'));
        $this->assertNull($this->SUT->edge('B', 'A'));
        $this->SUT->addEdge('A', 'B');
        $this->SUT->removeEdge('B', 'A');
        $this->assertNull($this->SUT->edge('A', 'B'));
        $this->assertNull($this->SUT->edge('B', 'A'));
        $this->assertNotContains('B', $this->SUT->vertices['A']->getNeighbors());
        $this->assertNotContains('A', $this->SUT->vertices['B']->getNeighbors());
    }

    public function testVertexGetSetValue()
    {
        $this->SUT->addVertex('A');
        $this->SUT->vertices['A']->setValue('testval1');
        $this->assertEquals('testval1', $this->SUT->vertices['A']->getValue());
        $this->SUT->addVertex('B');
        $this->SUT->vertices['B']->setValue('testval2');
        $this->assertEquals('testval2', $this->SUT->vertices['B']->getValue());
    }

    public function testEdgeGetSetValue()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addEdge('A', 'B');
        $this->SUT->addEdge('A', 'C');
        $this->SUT->edge('A', 'B')->setValue(1.0);
        $this->SUT->edge('A', 'C')->setValue(1.1);
        $this->SUT->edge('C', 'A')->setValue(2);
        $this->assertEquals(1.0, $this->SUT->edge('A', 'B')->getValue());
        $this->assertEquals(2, $this->SUT->edge('A', 'C')->getValue());
        $this->assertEquals(2, $this->SUT->edge('C', 'A')->getValue());
    }

    public function testVertexAdjacencyMethods()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addVertex('D');
        $this->SUT->addEdge('A', 'B');
        $this->SUT->addEdge('A', 'C');
        $this->SUT->addEdge('C', 'B');
        $this->assertEquals(true, $this->SUT->vertices['A']->adjacent('B'));
        $this->assertEquals(true, $this->SUT->vertices['B']->adjacent('A'));
        $this->assertEquals(true, $this->SUT->vertices['C']->adjacent('A'));
        $this->assertEquals(true, $this->SUT->vertices['A']->adjacent('C'));
        $this->assertEquals(false, $this->SUT->vertices['A']->adjacent('D'));
        $this->assertEquals(false, $this->SUT->vertices['D']->adjacent('A'));
    }

    public function testVertexAndEdgeCount()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addVertex('D');
        $this->SUT->removeVertex('D');
        $this->SUT->addEdge('A', 'B');
        $this->SUT->addEdge('A', 'C');
        $this->SUT->addEdge('C', 'A');
        $this->SUT->removeEdge('A', 'C');
        $this->assertEquals(3, $this->SUT->getVertexCount());
        $this->assertEquals(1, $this->SUT->getEdgeCount());
    }
}
