<?php

use PHPUnit\Framework\TestCase;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Algo\Dijkstra;
use GraphDS\Algo\FloydWarshall;
use GraphDS\Persistence\ImportGraph;
use GraphDS\Persistence\ExportGraph;

class DirectedGraphTest extends TestCase
{
    public function testVertexAddRemove()
    {
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

    public function testEdgeAddRemove()
    {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addEdge('v1', 'v2');
        $this->assertEquals(true, isset($g->edges['v1']['v2']));
        $this->assertEquals(false, isset($g->edges['v2']['v1']));
        $this->assertContains('v2', $g->vertices['v1']->getOutNeighbors());
        $this->assertContains('v1', $g->vertices['v2']->getInNeighbors());
        $this->assertNotContains('v2', $g->vertices['v1']->getInNeighbors());
        $this->assertNotContains('v1', $g->vertices['v2']->getOutNeighbors());
        $g->removeEdge('v1', 'v2');
        $this->assertEquals(false, isset($g->edges['v1']['v2']));
        $this->assertEquals(false, isset($g->edges['v2']['v1']));
        $this->assertNotContains('v2', $g->vertices['v1']->getOutNeighbors());
        $this->assertNotContains('v1', $g->vertices['v2']->getInNeighbors());
    }

    public function testVertexGetSetValue()
    {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->vertices['v1']->setValue('testval1');
        $this->assertEquals('testval1', $g->vertices['v1']->getValue());
        $g->addVertex('v2');
        $g->vertices['v2']->setValue('testval2');
        $this->assertEquals('testval2', $g->vertices['v2']->getValue());
    }

    public function testEdgeGetSetValue()
    {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v1');
        $g->edges['v1']['v2']->setValue(1.0);
        $g->edges['v1']['v3']->setValue(1.1);
        $g->edges['v3']['v1']->setValue(2);
        $this->assertEquals('1.0', $g->edges['v1']['v2']->getValue());
        $this->assertEquals('1.1', $g->edges['v1']['v3']->getValue());
        $this->assertEquals('2', $g->edges['v3']['v1']->getValue());
    }

    public function testVertexAdjacencyMethods()
    {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addVertex('v4');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v2');
        $this->assertEquals(true, $g->vertices['v1']->outAdjacent('v2'));
        $this->assertEquals(false, $g->vertices['v2']->outAdjacent('v1'));
        $this->assertEquals(true, $g->vertices['v3']->inAdjacent('v1'));
        $this->assertEquals(false, $g->vertices['v1']->inAdjacent('v3'));
        $this->assertEquals(true, $g->vertices['v1']->adjacent('v2'));
        $this->assertEquals(true, $g->vertices['v2']->adjacent('v1'));
        $this->assertEquals(true, $g->vertices['v3']->adjacent('v1'));
        $this->assertEquals(true, $g->vertices['v1']->adjacent('v3'));
        $this->assertEquals(false, $g->vertices['v1']->adjacent('v4'));
        $this->assertEquals(false, $g->vertices['v4']->adjacent('v1'));
    }

    public function testIndegreeAndOutdegree()
    {
        $g = new DirectedGraph();
        $g->addVertex('v1');
        $g->addVertex('v2');
        $g->addVertex('v3');
        $g->addEdge('v1', 'v2');
        $g->addEdge('v1', 'v3');
        $g->addEdge('v3', 'v1');
        $this->assertEquals(1, $g->vertices['v1']->getIndegree());
        $this->assertEquals(2, $g->vertices['v1']->getOutdegree());
        $this->assertEquals(1, $g->vertices['v2']->getIndegree());
        $this->assertEquals(0, $g->vertices['v2']->getOutdegree());
        $this->assertEquals(1, $g->vertices['v3']->getIndegree());
        $this->assertEquals(1, $g->vertices['v3']->getOutdegree());
    }

    public function testVertexAndEdgeCount()
    {
        $g = new DirectedGraph();
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
        $this->assertEquals(2, $g->getEdgeCount());
    }

    public function testDijkstra()
    {
        $g = new DirectedGraph();

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

        $this->assertEmpty($res_E['path']);
        $this->assertEquals(INF, $res_E['dist']);

        $res_C = $d->get('C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(40, $res_C['dist']);

        $d->run('B');
        $res_A = $d->get('A');
        $expected_path = array('B', 'F', 'C', 'D', 'G', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(70, $res_A['dist']);
    }

    public function testFloydWarshall()
    {
        $g = new DirectedGraph();

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

        $this->assertEmpty($res_E['path']);
        $this->assertEquals(null, $res_E['dist']);

        $res_C = $d->get('A', 'C');
        $expected_path = array('A', 'B', 'F', 'C');
        $this->assertEquals($expected_path, $res_C['path']);
        $this->assertEquals(40, $res_C['dist']);

        $res_A = $d->get('B', 'A');
        $expected_path = array('B', 'F', 'C', 'D', 'G', 'A');
        $this->assertEquals($expected_path, $res_A['path']);
        $this->assertEquals(70, $res_A['dist']);
    }

    public function testImportExport()
    {
        $g = new DirectedGraph();

        $g->addVertex('A');
        $g->addVertex('B');
        $g->addVertex('C');

        $g->addEdge('A', 'B', 20);
        $g->addEdge('A', 'C', 80);
        $g->addEdge('B', 'C', 90);
        $g->addEdge('C', 'B', 70);

        $e = new ExportGraph($g);
        $graphml = $e->getGraphML();
        $e->saveToFile($graphml, 'graphDirected.graphml');

        $i = new ImportGraph($g);
        $gi = $i->fromGraphML('graphDirected.graphml');

        $this->assertEquals($g, $gi);
    }
}
