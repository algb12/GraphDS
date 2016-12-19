<?php
/**
 * The GraphDS graph exporter.
 */
namespace GraphDS\Persistence;

use InvalidArgumentException;
use SimpleXMLElement;
use DOMDocument;

/**
 * Class defining the graph exporter methods.
 */
class ExportGraph
{
    /**
     * Reference to the graph.
     *
     * @var object
     */
    public $graph;

    /**
     * Constructor for the graph exporter.
     *
     * @param object $graph The graph to be exported
     */
    public function __construct($graph)
    {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException('Only GraphDS graphs can be exported.');
        }
        $this->graph = &$graph;
    }

    /**
     * Returns the GraphML output representing the graph.
     *
     * @param string $file The file to which the graph should be written
     */
    public function getGraphML()
    {
        $directionality = $this->graph->directed ? 'directed' : 'undirected';
        $export = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
                                      .'<graphml xmlns="http://graphml.graphdrawing.org/xmlns" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd">'
                                      .'</graphml>');

        $keyNode = $export->addChild('key');
        $keyNode->addAttribute('id', 'd0');
        $keyNode->addAttribute('for', 'node');
        $keyNode->addAttribute('attr.name', 'value');
        $keyNode->addAttribute('attr.type', 'string');
        $keyNode->addChild('default', '');

        $keyEdge = $export->addChild('key');
        $keyEdge->addAttribute('id', 'd1');
        $keyEdge->addAttribute('for', 'edge');
        $keyEdge->addAttribute('attr.name', 'weight');
        $keyEdge->addAttribute('attr.type', 'double');
        $keyEdge->addChild('default', 0.0);

        $graphElem = $export->addChild('graph');
        $graphElem->addAttribute('id', 'G');
        $graphElem->addAttribute('edgedefault', $directionality);
        $graphElem->addAttribute('parse.nodes', $this->graph->getVertexCount());
        $graphElem->addAttribute('parse.edges', $this->graph->getEdgeCount());
        $graphElem->addAttribute('parse.nodeids', 'free');
        $graphElem->addAttribute('parse.edgeids', 'free');
        $graphElem->addAttribute('parse.order', 'nodesfirst');

        foreach ($this->graph->vertices as $vertexKey => $vertex) {
            $node = $graphElem->addChild('node');
            $node->addAttribute('id', $vertexKey);
            if (null !== ($value = $vertex->getValue())) {
                $data = $node->addChild('data', $value);
                $data->addAttribute('key', 'd0');
            }
        }
        foreach ($this->graph->edges as $edgeSource) {
            foreach ($edgeSource as $edgeTarget) {
                $edge = $graphElem->addChild('edge');
                $edge->addAttribute('source', $edgeTarget->vertices['from']);
                $edge->addAttribute('target', $edgeTarget->vertices['to']);
                if (null !== ($value = $edgeTarget->getValue())) {
                    $data = $edge->addChild('data', $value);
                    $data->addAttribute('key', 'd1');
                }
            }
        }

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($export->asXML());

        return $dom->saveXML();
    }

    public function saveToFile($data, $file)
    {
        $dir = dirname($file);
        if (is_writable($dir)) {
            $fp = fopen($file, 'w');
            fwrite($fp, $data);
            fclose($fp);
        } else {
            throw new InvalidArgumentException('Directory '.$dir.' not writable. Cannot write to '.$file.'.');
        }
    }
}
