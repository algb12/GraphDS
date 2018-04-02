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
    public function testVertexAddRemove()
    {
        $g = new UndirectedGraph();
        $g->addVertex('A');
        $this->assertArrayHasKey('A', $g->vertices);
        $g->addVertex('B');
        $this->assertArrayHasKey('B', $g->vertices);
        $g->addVertex('C');
        $this->assertArrayHasKey('C', $g->vertices);
        $g->addEdge('A', 'B');
        $this->assertArrayHasKey('B', $g->edges['A']);
        $g->addEdge('A', 'C');
        $this->assertArrayHasKey('C', $g->edges['A']);
        $this->assertContains('C', $g->vertices['A']->getNeighbors());
        $g->removeVertex('C');
        $this->assertArrayNotHasKey('C', $g->vertices);
        $this->assertArrayNotHasKey('C', $g->edges['A']);
        $this->assertNotContains('C', $g->vertices['A']->getNeighbors());
        $g->removeVertex('B');
        $this->assertArrayNotHasKey('B', $g->vertices);
        $this->assertArrayNotHasKey('A', $g->edges);
    }

    public function testEdgeAddRemove()
    {
        $g = new UndirectedGraph();
        $g->addVertex('A');
        $g->addVertex('B');
        $g->addEdge('A', 'B');
        $this->assertNotNull($g->edge('A', 'B'));
        $this->assertNotNull($g->edge('B', 'A'));
        $this->assertContains('B', $g->vertices['A']->getNeighbors());
        $this->assertContains('A', $g->vertices['B']->getNeighbors());
        $g->removeEdge('A', 'B');
        $this->assertNull($g->edge('A', 'B'));
        $this->assertNull($g->edge('B', 'A'));
        $this->assertNotContains('B', $g->vertices['A']->getNeighbors());
        $this->assertNotContains('A', $g->vertices['B']->getNeighbors());
    }

    public function testVertexGetSetValue()
    {
        $g = new UndirectedGraph();
        $g->addVertex('A');
        $g->vertices['A']->setValue('testval1');
        $this->assertEquals('testval1', $g->vertices['A']->getValue());
        $g->addVertex('B');
        $g->vertices['B']->setValue('testval2');
        $this->assertEquals('testval2', $g->vertices['B']->getValue());
    }

    public function testEdgeGetSetValue()
    {
        $g = new UndirectedGraph();
        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addEdge('A', 'B');
        $g->addEdge('A', 'C');
        $g->edge('A', 'B')->setValue(1.0);
        $g->edge('A', 'C')->setValue(1.1);
        $g->edge('C', 'A')->setValue(2);
        $this->assertEquals(1.0, $g->edge('A', 'B')->getValue());
        $this->assertEquals(2, $g->edge('A', 'C')->getValue());
        $this->assertEquals(2, $g->edge('C', 'A')->getValue());
    }

    public function testVertexAdjacencyMethods()
    {
        $g = new UndirectedGraph();
        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addVertex('D');
        $g->addEdge('A', 'B');
        $g->addEdge('A', 'C');
        $g->addEdge('C', 'B');
        $this->assertEquals(true, $g->vertices['A']->adjacent('B'));
        $this->assertEquals(true, $g->vertices['B']->adjacent('A'));
        $this->assertEquals(true, $g->vertices['C']->adjacent('A'));
        $this->assertEquals(true, $g->vertices['A']->adjacent('C'));
        $this->assertEquals(false, $g->vertices['A']->adjacent('D'));
        $this->assertEquals(false, $g->vertices['D']->adjacent('A'));
    }

    public function testVertexAndEdgeCount()
    {
        $g = new UndirectedGraph();
        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addVertex('D');
        $g->removeVertex('D');
        $g->addEdge('A', 'B');
        $g->addEdge('A', 'C');
        $g->addEdge('C', 'A');
        $g->removeEdge('A', 'C');
        $this->assertEquals(3, $g->getVertexCount());
        $this->assertEquals(1, $g->getEdgeCount());
    }

    public function testDijkstra()
    {
        $g = new UndirectedGraph();

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
        $g = new UndirectedGraph();

        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addVertex('D');
        $g->addVertex('E');
        $g->addVertex('F');
        $g->addVertex('G');
        $g->addVertex('H');
        $g->addVertex('I');
        $g->addVertex('J');
        $g->addVertex('K');

        $g->addEdge('A', 'B', 5);
        $g->addEdge('A', 'C', 3);
        $g->addEdge('A', 'G', 3);
        $g->addEdge('B', 'F', 3);
        $g->addEdge('B', 'J', 5);
        $g->addEdge('B', 'K', 2);
        $g->addEdge('C', 'B', 3);
        $g->addEdge('C', 'D', 2);
        $g->addEdge('C', 'E', 1);
        $g->addEdge('D', 'F', 2);
        $g->addEdge('E', 'F', 3);
        $g->addEdge('F', 'I', 2);
        $g->addEdge('F', 'J', 3);
        $g->addEdge('G', 'E', 4);
        $g->addEdge('G', 'H', 3);
        $g->addEdge('H', 'I', 2);
        $g->addEdge('I', 'J', 2);
        $g->addEdge('I', 'K', 3);
        $g->addEdge('J', 'K', 7);

        $d = new DijkstraMulti($g);

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
        $g = new UndirectedGraph();

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
        $g = new UndirectedGraph();

        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addVertex('D');
        $g->addVertex('E');

        $g->addEdge('A', 'B', 1);
        $g->addEdge('A', 'C', 1);
        $g->addEdge('B', 'D', 1);
        $g->addEdge('C', 'E', 1);

        $d = new DFS($g);

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
        $g = new UndirectedGraph();

        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');
        $g->addVertex('D');
        $g->addVertex('E');

        $g->addEdge('A', 'B', 1);
        $g->addEdge('A', 'C', 1);
        $g->addEdge('B', 'D', 1);
        $g->addEdge('C', 'E', 1);

        $d = new BFS($g);

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
        $g = new UndirectedGraph();

        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');

        $g->addEdge('A', 'B', 20);
        $g->addEdge('A', 'C', 80);
        $g->addEdge('B', 'C', 90);
        $g->addEdge('C', 'B', 70);

        $e = new ExportGraph($g);
        $graphml = $e->getGraphML();
        $e->saveToFile($graphml, 'graphUndirected.graphml');

        $i = new ImportGraph($g);
        $gi = $i->fromGraphML('graphUndirected.graphml');

        $this->assertEquals($g, $gi);
    }
}
