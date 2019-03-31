<?php

namespace Tests\Graph;

use GraphDS\Graph\DirectedGraph;
use GraphDS\Algo\Dijkstra;
use GraphDS\Algo\DijkstraMulti;
use GraphDS\Algo\FloydWarshall;
use GraphDS\Algo\DFS;
use GraphDS\Algo\BFS;
use GraphDS\Persistence\ImportGraph;
use GraphDS\Persistence\ExportGraph;
use PHPUnit\Framework\TestCase;

class DirectedGraphTest extends TestCase
{
    /**
     * @var DirectedGraph
     */
    private $SUT;

    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new DirectedGraph();
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
        $this->assertContains('C', $this->SUT->vertices['A']->getOutNeighbors());
        $this->SUT->removeVertex('C');
        $this->assertArrayNotHasKey('C', $this->SUT->vertices);
        $this->assertArrayNotHasKey('C', $this->SUT->edges['A']);
        $this->assertNotContains('C', $this->SUT->vertices['A']->getOutNeighbors());
        $this->SUT->removeVertex('B');
        $this->assertArrayNotHasKey('B', $this->SUT->vertices);
        $this->assertArrayNotHasKey('A', $this->SUT->edges);
    }

    public function testEdgeAddRemove()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addEdge('A', 'B');
        $this->assertEquals(true, isset($this->SUT->edges['A']['B']));
        $this->assertEquals(false, isset($this->SUT->edges['B']['A']));
        $this->assertContains('B', $this->SUT->vertices['A']->getOutNeighbors());
        $this->assertContains('A', $this->SUT->vertices['B']->getInNeighbors());
        $this->assertNotContains('B', $this->SUT->vertices['A']->getInNeighbors());
        $this->assertNotContains('A', $this->SUT->vertices['B']->getOutNeighbors());
        $this->SUT->removeEdge('A', 'B');
        $this->assertEquals(false, isset($this->SUT->edges['A']['B']));
        $this->assertEquals(false, isset($this->SUT->edges['B']['A']));
        $this->assertNotContains('B', $this->SUT->vertices['A']->getOutNeighbors());
        $this->assertNotContains('A', $this->SUT->vertices['B']->getInNeighbors());
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
        $this->SUT->addEdge('C', 'A');
        $this->SUT->edges['A']['B']->setValue(1.0);
        $this->SUT->edges['A']['C']->setValue(1.1);
        $this->SUT->edges['C']['A']->setValue(2);
        $this->assertEquals('1.0', $this->SUT->edges['A']['B']->getValue());
        $this->assertEquals('1.1', $this->SUT->edges['A']['C']->getValue());
        $this->assertEquals('2', $this->SUT->edges['C']['A']->getValue());
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
        $this->assertEquals(true, $this->SUT->vertices['A']->outAdjacent('B'));
        $this->assertEquals(false, $this->SUT->vertices['B']->outAdjacent('A'));
        $this->assertEquals(true, $this->SUT->vertices['C']->inAdjacent('A'));
        $this->assertEquals(false, $this->SUT->vertices['A']->inAdjacent('C'));
        $this->assertEquals(true, $this->SUT->vertices['A']->adjacent('B'));
        $this->assertEquals(true, $this->SUT->vertices['B']->adjacent('A'));
        $this->assertEquals(true, $this->SUT->vertices['C']->adjacent('A'));
        $this->assertEquals(true, $this->SUT->vertices['A']->adjacent('C'));
        $this->assertEquals(false, $this->SUT->vertices['A']->adjacent('D'));
        $this->assertEquals(false, $this->SUT->vertices['D']->adjacent('A'));
    }

    public function testIndegreeAndOutdegree()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addEdge('A', 'B');
        $this->SUT->addEdge('A', 'C');
        $this->SUT->addEdge('C', 'A');
        $this->assertEquals(1, $this->SUT->vertices['A']->getIndegree());
        $this->assertEquals(2, $this->SUT->vertices['A']->getOutdegree());
        $this->assertEquals(1, $this->SUT->vertices['B']->getIndegree());
        $this->assertEquals(0, $this->SUT->vertices['B']->getOutdegree());
        $this->assertEquals(1, $this->SUT->vertices['C']->getIndegree());
        $this->assertEquals(1, $this->SUT->vertices['C']->getOutdegree());
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
        $this->assertEquals(2, $this->SUT->getEdgeCount());
    }

    public function testTranspose()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addVertex('D');
        $this->SUT->addVertex('E');

        $this->SUT->addEdge('A', 'B', 1);
        $this->SUT->addEdge('A', 'C', 1);
        $this->SUT->addEdge('B', 'D', 1);
        $this->SUT->addEdge('C', 'E', 1);

        $originalGraph = clone $this->SUT;
        $transposedGraph = $this->SUT->getTranspose();

        $this->assertEquals($this->SUT, $originalGraph);
        $this->assertNull($this->SUT->edge('B', 'A'));
        $this->assertNotNull($this->SUT->edge('A', 'B'));
        $this->assertEquals(1, $this->SUT->edge('A', 'B')->getValue());
        $this->assertNull($transposedGraph->edge('A', 'B'));
        $this->assertNotNull($transposedGraph->edge('B', 'A'));
        $this->assertEquals(1, $transposedGraph->edge('B', 'A')->getValue());
    }
}
