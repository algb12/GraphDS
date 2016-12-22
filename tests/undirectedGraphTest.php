<?php

use PHPUnit\Framework\TestCase;
use GraphDS\Graph\UndirectedGraph;
use GraphDS\Algo\Dijkstra;
use GraphDS\Algo\FloydWarshall;
use GraphDS\Persistence\ImportGraph;
use GraphDS\Persistence\ExportGraph;

class UndirectedGraphTest extends TestCase
{
    public function testVertexAddRemove()
    {
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

    public function testEdgeAddRemove()
    {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addEdge('v1', 'v2');
        $this->assertNotNull($g->edge('v1', 'v2'));
        $this->assertNotNull($g->edge('v2', 'v1'));
        $this->assertContains('v2', $g->vertices['v1']->getNeighbors());
        $this->assertContains('v1', $g->vertices['v2']->getNeighbors());
        $g->removeEdge('v1', 'v2');
        $this->assertNull($g->edge('v1', 'v2'));
        $this->assertNull($g->edge('v2', 'v1'));
        $this->assertNotContains('v2', $g->vertices['v1']->getNeighbors());
        $this->assertNotContains('v1', $g->vertices['v2']->getNeighbors());
    }

    public function testVertexGetSetValue()
    {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->vertices['v1']->setValue('testval1');
        $this->assertEquals('testval1', $g->vertices['v1']->getValue());
        $g->addVertex('v2');
        $g->vertices['v2']->setValue('testval2');
        $this->assertEquals('testval2', $g->vertices['v2']->getValue());
    }

    public function testEdgeGetSetValue()
    {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->edge('v1', 'v2')->setValue(1.0);
        $g->edge('v1', 'v3')->setValue(1.1);
        $g->edge('v3', 'v1')->setValue(2);
        $this->assertEquals(1.0, $g->edge('v1', 'v2')->getValue());
        $this->assertEquals(2, $g->edge('v1', 'v3')->getValue());
        $this->assertEquals(2, $g->edge('v3', 'v1')->getValue());
    }

    public function testVertexAdjacencyMethods()
    {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addVertex('v4');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v2');
        $this->assertEquals(true, $g->vertices['v1']->adjacent('v2'));
        $this->assertEquals(true, $g->vertices['v2']->adjacent('v1'));
        $this->assertEquals(true, $g->vertices['v3']->adjacent('v1'));
        $this->assertEquals(true, $g->vertices['v1']->adjacent('v3'));
        $this->assertEquals(false, $g->vertices['v1']->adjacent('v4'));
        $this->assertEquals(false, $g->vertices['v4']->adjacent('v1'));
    }

    public function testVertexAndEdgeCount()
    {
        $g = new UndirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addVertex('v4');
        $g->removeVertex('v4');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v1');
        $g->removeEdge('v1', 'v3');
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
