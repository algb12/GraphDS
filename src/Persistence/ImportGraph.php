<?php
/**
 * The GraphDS graph importer.
 */
namespace GraphDS\Persistence;

use GraphDS\Graph\Graph;
use GraphDS\Graph\DirectedGraph;
use GraphDS\Graph\UndirectedGraph;
use InvalidArgumentException;
use SimpleXMLElement;

/**
 * Class defining the graph importer methods.
 */
class ImportGraph
{
    /**
     * Returns GraphDS graph represented by GraphML.
     *
     * @param string $file File containing the GraphML markup
     *
     * @return Graph The graph reconstructed from the GraphML
     */
    public function fromGraphML($file)
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException('File '.$file.' does not exist.');
        }
        $importRaw = file_get_contents($file);

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
            if (empty($value)) {
                $default = $this->import->xpath('key[@for="node"]/default');
                if (!empty($default)) {
                    $value = (string) $default;
                }
            }
            $graph->addVertex($vertex);
            $graph->vertices[$vertex]->setValue($value);
        }

        foreach ($this->import->graph->edge as $edge) {
            $edgeSource = (string) $edge['source'];
            $edgeTarget = (string) $edge['target'];
            $value = (string) $edge->data;
            if (empty($value)) {
                $default = $this->import->xpath('key[@for="edge"]/default');
                if (!empty($default)) {
                    $value = (string) $default;
                }
            }
            $graph->addEdge($edgeSource, $edgeTarget);
            $graph->edge($edgeSource, $edgeTarget)->setValue($value);
        }

        return $graph;
    }
}
