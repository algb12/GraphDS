<?php

require __DIR__.'/vendor/autoload.php';

use GraphDS\Graph\DirectedGraph;
use GraphDS\Algo\Dijkstra;

$g = new DirectedGraph;

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

$res = $d->calcDijkstra('A');

echo '<pre>';
print_r($res['path']['D']);
echo '</pre>';

echo 'Shortest distance from A to D: '.$res['dist']['D'];
