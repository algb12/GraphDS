<?php

namespace GraphDS\Graph;

use GraphDS\Vertex\DirectedVertex;
use GraphDS\Edge\DirectedEdge;

class DirectedGraph extends Graph {
    /**
     * [__construct Constructor for DirectedGraph object]
     */
    function __construct() {
        parent::__construct();
        $this->directed = true;
    }

    /**
     * [addVertex Adds a directed vertex to the graph]
     * @param string $v ID of the vertex
     */
    public function addVertex($v) {
        if (empty($this->vertices[$v])) {
            $this->vertices[$v] = new DirectedVertex();
        }
    }

    /**
     * [removeVertex Removes a directed vertex from the graph]
     * @param string $v ID of the vertex
     */
    public function removeVertex($v) {
        if (isset($this->vertices[$v])) {
            $neighbors = $this->vertices[$v]->getNeighbors();
            foreach ($neighbors['out'] as $neighbor) {
                if(($key = array_search($v, $this->vertices[$neighbor]->neighbors['in'])) !== false) {
                    unset($this->vertices[$neighbor]->neighbors['in'][$key]);
                }
            }
            foreach ($neighbors['in'] as $neighbor) {
                if(($key = array_search($v, $this->vertices[$neighbor]->neighbors['out'])) !== false) {
                    unset($this->vertices[$neighbor]->neighbors['out'][$key]);
                }
                if (isset($this->edges[$neighbor][$v])) {
                    $this->removeEdge($neighbor, $v);
                }
            }
            unset($this->edges[$v]);
            unset($this->vertices[$v]);
        }
    }

    /**
     * [addEdge Adds a directed edge between two directed vertices ($v1 to $v2)]
     * @param string $v1 ID of first vertex
     * @param string $v2 ID of second vertex
     */
    public function addEdge($v1, $v2) {
        if ($v1 !== $v2) {
            if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
                if (empty($this->edges[$v1][$v2])) {
                    $this->edges[$v1][$v2] = new DirectedEdge($v1, $v2);
                    $this->vertices[$v1]->addOutNeighbor($v2);
                    $this->vertices[$v2]->addInNeighbor($v1);
                }
            } else {
                trigger_error("Error: one of the vertices does not exist.", E_USER_ERROR);
            }
        } else {
            trigger_error("Error: cannot connect vertex to itself.", E_USER_ERROR);
        }
    }

    /**
     * [addEdge Removes a directed edge between two directed vertices ($v1 to $v2)]
     * @param string $v1 ID of first vertex
     * @param string $v2 ID of second vertex
     */
    public function removeEdge($v1, $v2) {
        if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
            if (isset($this->edges[$v1][$v2])) {
                if(($key = array_search($v2, $this->vertices[$v1]->neighbors['out'])) !== false) {
                    unset($this->vertices[$v1]->neighbors['out'][$key]);
                }
                if(($key = array_search($v1, $this->vertices[$v2]->neighbors['in'])) !== false) {
                    unset($this->vertices[$v2]->neighbors['in'][$key]);
                }
                if (isset($this->edges[$v1][$v2])) {
                    unset($this->edges[$v1][$v2]);
                    if (empty($this->edges[$v1])) {
                        unset($this->edges[$v1]);
                    }
                }
            } else {
                trigger_error("Error: no edge between $v1 and $v2.", E_USER_ERROR);
            }
        } else {
            trigger_error("Error: one of the vertices does not exist.", E_USER_ERROR);
        }
    }

    /**
     * [getEdgeValue Returns the value associated with the edge between two directed vertices (e.g. cost, $v1 to $v2)]
     * @param  string $v1 ID of first vertex
     * @param  string $v2 ID of second vertex
     * @return string     Value associated with the edge between $v1 and $v2
     */
    public function getEdgeValue($v1, $v2) {
        if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
            if (isset($this->edges[$v1][$v2])) {
                return $this->edges[$v1][$v2]->getValue();
            }
        } else {
            trigger_error("Error: one of the vertices does not exist.", E_USER_ERROR);
        }
    }

    /**
     * [setEdgeValue Sets the value associated with the edge between two directed vertices (e.g. cost, $v1 to $v2)]
     * @param string $v1    ID of first vertex
     * @param string $v2    ID of second vertex
     * @param string $value Value to be set for the edge between $v1 and $v2
     */
    public function setEdgeValue($v1, $v2, $value) {
        if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
            if (isset($this->edges[$v1][$v2])) {
                $this->edges[$v1][$v2]->setValue($value);
            } else {
                trigger_error("Error: specified edge does not exist.", E_USER_ERROR);
            }
        } else {
            trigger_error("Error: one of the vertices does not exist.", E_USER_ERROR);
        }
    }
}
