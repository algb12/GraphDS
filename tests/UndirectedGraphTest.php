<?php

use GraphDS\Graph\UndirectedGraph;
use GraphDS\Algo\Dijkstra;
use GraphDS\Algo\DijkstraMulti;
use GraphDS\Algo\FloydWarshall;
use GraphDS\Algo\DFS;
use GraphDS\Algo\BFS;
use GraphDS\Persistence\ImportGraph;
use GraphDS\Persistence\ExportGraph;
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

    public function testDijkstra()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addVertex('D');
        $this->SUT->addVertex('E');
        $this->SUT->addVertex('F');
        $this->SUT->addVertex('G');
        $this->SUT->addVertex('H');

        $this->SUT->addEdge('A', 'B', 20);
        $this->SUT->addEdge('A', 'D', 80);
        $this->SUT->addEdge('A', 'G', 90);
        $this->SUT->addEdge('B', 'F', 10);
        $this->SUT->addEdge('C', 'D', 10);
        $this->SUT->addEdge('C', 'F', 50);
        $this->SUT->addEdge('C', 'H', 20);
        $this->SUT->addEdge('D', 'C', 10);
        $this->SUT->addEdge('D', 'G', 20);
        $this->SUT->addEdge('E', 'B', 50);
        $this->SUT->addEdge('E', 'G', 30);
        $this->SUT->addEdge('F', 'C', 10);
        $this->SUT->addEdge('F', 'D', 40);
        $this->SUT->addEdge('G', 'A', 20);

        $d = new Dijkstra($this->SUT);

        $d->run('A');
        $res_E = $d->get('E');

        $this->assertNotEmpty($res_E['path']);
        $expected_path = array('A', 'B', 'E');
        $this->assertEquals($expected_path, $res_E['path']);
        $this->assertEquals(70, $res_E['dist']);

        $res_C = $d->get('C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(80, $res_C['dist']);

        $d->run('B');
        $res_A = $d->get('A');
        $expected_path = array('B', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(20, $res_A['dist']);
    }

    public function testDijkstraMulti()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addVertex('D');
        $this->SUT->addVertex('E');
        $this->SUT->addVertex('F');
        $this->SUT->addVertex('G');
        $this->SUT->addVertex('H');
        $this->SUT->addVertex('I');
        $this->SUT->addVertex('J');
        $this->SUT->addVertex('K');

        $this->SUT->addEdge('A', 'B', 5);
        $this->SUT->addEdge('A', 'C', 3);
        $this->SUT->addEdge('A', 'G', 3);
        $this->SUT->addEdge('B', 'F', 3);
        $this->SUT->addEdge('B', 'J', 5);
        $this->SUT->addEdge('B', 'K', 2);
        $this->SUT->addEdge('C', 'B', 3);
        $this->SUT->addEdge('C', 'D', 2);
        $this->SUT->addEdge('C', 'E', 1);
        $this->SUT->addEdge('D', 'F', 2);
        $this->SUT->addEdge('E', 'F', 3);
        $this->SUT->addEdge('F', 'I', 2);
        $this->SUT->addEdge('F', 'J', 3);
        $this->SUT->addEdge('G', 'E', 4);
        $this->SUT->addEdge('G', 'H', 3);
        $this->SUT->addEdge('H', 'I', 2);
        $this->SUT->addEdge('I', 'J', 2);
        $this->SUT->addEdge('I', 'K', 3);
        $this->SUT->addEdge('J', 'K', 7);

        $d = new DijkstraMulti($this->SUT);

        $d->run('A');
        $res_J = $d->get('J');

        $this->assertNotEmpty($res_J['paths']);
        $expected_paths = array(
            array('A', 'B', 'J'),
            array('A', 'C', 'E', 'F', 'J'),
            array('A', 'C', 'D', 'F', 'J'),
            array('A', 'G', 'H', 'I', 'J')
        );
        $this->assertEquals($expected_paths, $res_J['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(10, $res_J['dist']);

        $d->run('C');
        $res_J = $d->get('J');
        $expected_paths = array(
            array('C', 'D', 'F', 'J'),
            array('C', 'E', 'F', 'J')
        );
        $this->assertEquals($expected_paths, $res_J['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(7, $res_J['dist']);

        $d->run('J');
        $res_C = $d->get('C');
        $expected_paths = array(
            array('J', 'F', 'D', 'C'),
            array('J', 'F', 'E', 'C')
        );
        $this->assertEquals($expected_paths, $res_C['paths'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals(7, $res_C['dist']);
    }

    public function testFloydWarshall()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');
        $this->SUT->addVertex('D');
        $this->SUT->addVertex('E');
        $this->SUT->addVertex('F');
        $this->SUT->addVertex('G');
        $this->SUT->addVertex('H');

        $this->SUT->addEdge('A', 'B', 20);
        $this->SUT->addEdge('A', 'D', 80);
        $this->SUT->addEdge('A', 'G', 90);
        $this->SUT->addEdge('B', 'F', 10);
        $this->SUT->addEdge('C', 'D', 10);
        $this->SUT->addEdge('C', 'F', 50);
        $this->SUT->addEdge('C', 'H', 20);
        $this->SUT->addEdge('D', 'C', 10);
        $this->SUT->addEdge('D', 'G', 20);
        $this->SUT->addEdge('E', 'B', 50);
        $this->SUT->addEdge('E', 'G', 30);
        $this->SUT->addEdge('F', 'C', 10);
        $this->SUT->addEdge('F', 'D', 40);
        $this->SUT->addEdge('G', 'A', 20);

        $d = new FloydWarshall($this->SUT);

        $d->run();
        $res_E = $d->get('A', 'E');

        $this->assertNotEmpty($res_E['path']);
        $expected_path = array('A', 'B', 'E');
        $this->assertEquals($expected_path, $res_E['path']);
        $this->assertEquals(70, $res_E['dist']);

        $res_C = $d->get('A', 'C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(80, $res_C['dist']);

        $res_A = $d->get('B', 'A');
        $expected_path = array('B', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(20, $res_A['dist']);
    }

    public function testDFS()
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

        $d = new DFS($this->SUT);

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

        $expected_discovered = array('A', 'C', 'E', 'B', 'D');
        $this->assertEquals($expected_discovered, $res_A['discovered']);
    }

    public function testBFS()
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

        $d = new BFS($this->SUT);

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

    public function testImportExport()
    {
        $this->SUT->addVertex('A');
        $this->SUT->addVertex('B');
        $this->SUT->addVertex('C');

        $this->SUT->addEdge('A', 'B', 20);
        $this->SUT->addEdge('A', 'C', 80);
        $this->SUT->addEdge('B', 'C', 90);
        $this->SUT->addEdge('C', 'B', 70);

        $e = new ExportGraph($this->SUT);
        $graphMl = $e->getGraphML();
        $e->saveToFile($graphMl, 'graphUndirected.graphml');

        $i = new ImportGraph();
        $gi = $i->fromGraphML('graphUndirected.graphml');

        $this->assertEquals($this->SUT, $gi);
    }
}
