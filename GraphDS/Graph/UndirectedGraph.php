<?php

namespace GraphDS\Graph;

use GraphDS\Vertex\UndirectedVertex;
use GraphDS\Edge\UndirectedEdge;

class UndirectedGraph extends Graph {
    /**
     * [__construct Constructor for UndirectedGraph object]
     */
    function __construct() {
        parent::__construct();
        $this->directed = false;
    }

    /**
     * [addVertex Adds an undirected vertex to the graph]
     * @param string $v ID of the vertex
     */
    public function addVertex($v) {
        if (empty($this->vertices[$v])) {
            $this->vertices[$v] = new UndirectedVertex();
        }
    }

    /**
     * [removeVertex Removes an undirected vertex from the graph]
     * @param string $v ID of the vertex
     */
    public function removeVertex($v) {
        if (isset($this->vertices[$v])) {
            $neighbors = $this->vertices[$v]->getNeighbors();
            foreach ($neighbors as $neighbor) {
                if(($key = array_search($v, $this->vertices[$neighbor]->neighbors)) !== false) {
                    unset($this->vertices[$neighbor]->neighbors[$key]);
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
     * [addEdge Adds an undirected edge between two undirected vertices]
     * @param string $v1 ID of first vertex
     * @param string $v2 ID of second vertex
     */
    public function addEdge($v1, $v2) {
        if ($v1 !== $v2) {
            if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
                if (empty($this->edges[$v1][$v2]) || empty($this->edges[$v2][$v1])) {
                    $this->edges[$v1][$v2] = new UndirectedEdge($v1, $v2);
                    $this->edges[$v2][$v1] = $this->edges[$v1][$v2];
                    $this->vertices[$v1]->addNeighbor($v2);
                    $this->vertices[$v2]->addNeighbor($v1);
                }
            } else {
                trigger_error("Error: one of the vertices does not exist.", E_USER_ERROR);
            }
        } else {
            trigger_error("Error: cannot connect vertex to itself.", E_USER_ERROR);
        }
    }

    /**
     * [removeEdge Removes an undirected edge between two undirected vertices]
     * @param string $v1 ID of first undirected vertex
     * @param string $v2 ID of second undirected vertex
     */
    public function removeEdge($v1, $v2) {
        if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
            if (isset($this->edges[$v1][$v2]) || isset($this->edges[$v2][$v1])) {
                if(($key = array_search($v2, $this->vertices[$v1]->neighbors)) !== false) {
                    unset($this->vertices[$v1]->neighbors[$key]);
                }
                if(($key = array_search($v1, $this->vertices[$v2]->neighbors)) !== false) {
                    unset($this->vertices[$v2]->neighbors[$key]);
                }
                if (isset($this->edges[$v1][$v2])) {
                    unset($this->edges[$v1][$v2]);
                    if (empty($this->edges[$v1])) {
                        unset($this->edges[$v1]);
                    }
                }
                if (isset($this->edges[$v2][$v1])) {
                    unset($this->edges[$v2][$v1]);
                    if (empty($this->edges[$v2])) {
                        unset($this->edges[$v2]);
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
     * [getEdgeValue Returns the value associated with the edge between two undirected vertices (e.g. cost)]
     * @param  string $v1 ID of first vertex
     * @param  string $v2 ID of second vertex
     * @return string     Value associated with the edge between $v1 and $v2
     */
    public function getEdgeValue($v1, $v2) {
        if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
            if (isset($this->edges[$v1][$v2])) {
                return $this->edges[$v1][$v2]->getValue();
            } else if (isset($this->edges[$v2][$v1])) {
                return $this->edges[$v2][$v1]->getValue();
            }
        } else {
            trigger_error("Error: one of the vertices does not exist.", E_USER_ERROR);
        }
    }

    /**
     * [setEdgeValue Sets the value associated with the edge between two undirected vertices (e.g. cost)]
     * @param string $v1    ID of first vertex
     * @param string $v2    ID of second vertex
     * @param string $value Value to be set for the edge between $v1 and $v2
     */
    public function setEdgeValue($v1, $v2, $value) {
        if (isset($this->vertices[$v1]) && isset($this->vertices[$v2])) {
            if (isset($this->edges[$v1][$v2])) {
                $this->edges[$v1][$v2]->setValue($value);
            } else if (isset($this->edges[$v2][$v1])) {
                $this->edges[$v2][$v1]->setValue($value);
            } else {
                trigger_error("Error: specified edge does not exist.", E_USER_ERROR);
            }
        } else {
            trigger_error("Error: one of the vertices does not exist.", E_USER_ERROR);
        }
    }
}
