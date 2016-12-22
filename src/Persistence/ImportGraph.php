<?php
/**
 * The GraphDS graph importer.
 */
namespace GraphDS\Persistence;

use InvalidArgumentException;
use SimpleXMLElement;
use GraphDS\Graph\UndirectedGraph;
use GraphDS\Graph\DirectedGraph;

/**
 * Class defining the graph importer methods.
 */
class ImportGraph
{
    /**
     * Reference to the graph.
     *
     * @var object
     */
    public $graph;

    /**
     * Constructor for the graph importer.
     *
     * @param object $graph The graph into which data should be imported
     */
    public function __construct($graph)
    {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException('Only GraphDS graphs can be exported.');
        }
        $this->graph = &$graph;
    }

    /**
     * Returns GraphDS graph represented by GraphML.
     *
     * @param string $file File containing the GraphML markup
     *
     * @return object The graph reconstructed from the GraphML
     */
    public function fromGraphML($file)
    {
        if (file_exists($file)) {
            $importRaw = file_get_contents($file);
        } else {
            throw new InvalidArgumentException('File '.$file.' does not exist.');
        }

        $this->import = new SimpleXMLElement($importRaw);
        $directionality = (string) $this->import->graph['edgedefault'];

        if ($directionality === 'directed') {
            $graph = new DirectedGraph();
        } elseif ($directionality === 'undirected') {
            $graph = new UndirectedGraph();
        }

        foreach ($this->import->graph->node as $node) {
            $vertex = (string) $node['id'];
            $value = (string) $node->data;
            if (empty($value = (string) $node->data)) {
                if (!empty($this->import->xpath('key[@for="node"]/default'))) {
                    $value = (string) $this->import->xpath('key[@for="node"]/default');
                }
            }
            $graph->addVertex($vertex);
            $graph->vertices[$vertex]->setValue($value);
        }

        foreach ($this->import->graph->edge as $edge) {
            $edgeSource = (string) $edge['source'];
            $edgeTarget = (string) $edge['target'];
            if (empty($value = (string) $edge->data)) {
                if (!empty($this->import->xpath('key[@for="edge"]/default'))) {
                    $value = (string) $this->import->xpath('key[@for="edge"]/default');
                }
            }
            $graph->addEdge($edgeSource, $edgeTarget);
            $graph->edge($edgeSource, $edgeTarget)->setValue($value);
        }

        return $graph;
    }
}
