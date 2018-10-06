<?php

namespace Tests\Persistence;

use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\Graph;
use GraphDS\Graph\UndirectedGraph;
use GraphDS\Persistence\ExportGraph;
use GraphDS\Persistence\ImportGraph;
use PHPUnit\Framework\TestCase;
use Tests\Traits\GraphInteractionTrait;

class ImportExportGraphTest extends TestCase
{
    use GraphInteractionTrait;

    /**
     * @param Graph $graph
     *
     * @dataProvider provideGraphForImportAndExport
     */
    public function testImportExportForDirectedGraph(Graph $graph)
    {
        $this->addVerticesAndEdgesForShortestPathTests($graph);

        $graphExporter = new ExportGraph($graph);
        $graphMl = $graphExporter->getGraphML();
        $graphExporter->saveToFile($graphMl, 'graphDirected.graphml');

        $graphImporter = new ImportGraph();
        $importedGraph = $graphImporter->fromGraphML('graphDirected.graphml');

        $this->assertEquals($graph, $importedGraph);
    }


    /**
     * @return array
     */
    public function provideGraphForImportAndExport()
    {
        return array(
            array(new DirectedGraph()),
            array(new UndirectedGraph())
        );
    }


}