# GraphDS
[![Code Climate](https://codeclimate.com/github/algb12/GraphDS/badges/gpa.svg)](https://codeclimate.com/github/algb12/GraphDS)

## What is GraphDS and why was it created?
GraphDS is an object-oriented, lightweight implementation of the graph data-structure in PHP.

In a project of mine, I needed a way to represent graphs in PHP. None of the existing solutions have suited me, so I have decided to write my own graph library from scratch. The original implementation used in my project contains additional functions for graph traversal and refactoring of graphs, but these functions are specific to my project.

This version of GraphDS is a toned-down version of my original implementation. It makes use of OOP practices to allow algorithms to be loaded only on demand, making GraphDS fast, extendable and lightweight at the same time.

## How to install
Simply require the Composer package. In the directory of your project, run:

`composer require algb12/graph-ds`

## What is it even useful for?
Please see the sample app in the `SampleApp_RoadPlanner` directory to find a primitive application of GraphDS. The RoadPlanner app calculates the shortest road between two cities, using Dijkstra's and the Floyd-Warshall algorithm.

## Basic syntax
GraphDS has functions to create vertices and edges for both, undirected and directed graphs. The user does not need to worry about which type of edge/vertex is created, as this is all abstracted away under the relevant classes.

The following is a quick primer on the usage of GraphDS:

### Directed and undirected graphs
In graph theory, there are two main types of graphs. On one hand, there are directed graphs. A directed graph has vertices connected with a one-way edge. Think of a directed graph as a one-way lane, you can start at any vertex, but can the only follow along the directionality of each edge to the neighboring vertex.

On the other hand, there are undirected graphs. They allow you to get from any vertex to any neighbor, as in undirected graphs, there is no concept of ancestors and descendants. Vertices are simply connected with each other by directionless edges.

The following shows how to initialize a directed graph:

```
<?php

$g = new DirectedGraph();
```

Note that a graph is an object, and any vertices and edges are contained within this object.

In a similar manner, an undirected graph can be initialized, by creating an instance of the `UndirectedGraph` object in place of `DirectedGraph`.

### Vertices
In any graph, all vertices can be accessed through the `$g->vertices` array, where `$g` is an instance of a graph object. So, to access vertex `v1`, the syntax would be `$g->vertices['v1']`.

#### Adding and removing vertices
Adding and removing vertices can be accomplished using the `$g->addVertex('v')` and `$g->removeVertex('v')` methods, where `v` is the name of the vertex to be added/removed.

#### Getting and setting the value of a vertex
To get the value of a vertex, `$g->vertices['v']->getValue()` can be called. To set the value of a vertex, `$g->vertices['v']->setValue(value)` can be called, where `value` is stored as a string.

#### Getting neighbors of a vertex
Getting the neighbors of a vertex in an undirected graph can be accomplished by using `$g->vertices['v']->getNeighbors()`.

In a directed graph, `$g->vertices['v']->getInNeighbors` returns an array of all vertices connected by an incoming edge, whereas `$g->vertices['v']->getOutNeighbors()` returns an array of all vertices connected by an outgoing edge to the current vertex. `$g->vertices['v']->getNeighbors()` returns an array of two subarrays, `in` and `out` for incoming and outgoing vertices.

#### Indegrees and Outdegrees
The indegree and outdegree of a vertex is simply how many incoming and outgoing vertices are connected to a vertex, respectively.

The indegree and outdegree of a vertex can be easily determined using `$g->vertices['v']->getIndegree` and `$g->vertices['v']->getOutdegree`.

#### Asserting vertex adjacency
In any graph, asserting that vertex `v2` is adjacent to `v1` can be done by calling `$g->vertices['v1']->adjacent('v2')`. Note that in a directed graph, a vertex will be considered as adjacent using the `adjacent` method, no matter whether it is incoming or outgoing.

In a directed graph, inward and outward adjacency can be asserted by using `$g->vertices['v1']->inAdjacent('v2')` and `$g->vertices['v1']->outAdjacent('v2')`, respectively.

### Edges
Edges are the objects that connect vertices together. Note that in GraphDS, edges are _not actual_ connections between vertices, but merely _abstract_ connections, meaning that they are stored as separate objects to the vertices.

Edges can be accessed easily via  `$g->edge('v1', 'v2')`, where `v1` and `v2` are the vertices connected by the edge. Note that in an undirected graph, `$g->edge('v1', 'v2')` and `$g->edge('v2', 'v1')` are equivalent, whereas in directed graphs, they represent 2 distinct edges.

#### Real and virtual edges
In GraphDS, edges are stored as object, and each object takes up space. To reduce the spatial footprint, the `edge` function was introduced in version 1.0.3, which returns both, real edges and "virtual" edges.

Real edges are actual `DirectedEdge` or `UndirectedEdge` objects, whereas virtual edges are _not_ actually edge objects, but merely a result of the `edge` function returning edge `('v1', 'v2')`, even when `('v2', 'v1')` is requested. A virtual edge can be modified just like a real edge, with the exception of being removed. Virtual edges are not part of directed graphs.

This is desired behavior, as in undirected graphs, edge `('v1', 'v2')` would be equivalent to `('v2', 'v1')`. Virtual edges also eliminate the problem of edge duplication in undirected graphs, and therefore also reduce the memory GraphDS takes up.

#### Adding and removing edges
To add an edge, simply call `$g->addEdge('v1', 'v2', 'value')`, where `v1` and `v2` are the vertices in graph `$g` to be connected by this edge, and `value` an optional value for the edge, which must be numeric. The default value of edges is `null`.

Note that in undirected graphs, this also adds a "virtual" edge `('v2', 'v1')` in addition to `('v1', 'v2')`.

Removing an edge can be accomplished via `$g->removeEdge('v1', 'v2')`. Due to the behavior of virtual edges, this will remove both, the real edge `('v1', 'v2')` and the virtual edge `('v1', 'v2')` in an undirected graph, but only the real edge in a directed graph.

#### Getting and setting the value of an edge
To get the value of an edge, `$g->edge('v1', 'v2')->getValue()` can be called. To set the value of an edge, `$g->edge('v1', 'v2')->setValue(value)` can be called, where `value` can be any storable data-type.

## Algorithms
Since version 1.0.1, GraphDS has support for algorithms. One such algorithm already shipped with GraphDS is Dijkstra's shortest path algorithm for solving the shortest path problem.
`GraphDS\Algo` is the namespace of algorithms in GraphDS.

### Dijkstra's shortest path algorithm
In GraphDS, algorithms are treated as separate objects modifying the graph. This is what makes GraphDS lean and streamlined, as algorithms only have to be loaded into memory if they are needed, as they are not intrinsic to a graph object.

To use Dijkstra's algorithm, use the `GraphDS\Algo\Dijkstra` class.

For a directed graph `$g`, this is how Dijkstra's algorithm could be used:

```
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

$d->calcDijkstra('A');
$res_D = $d->getPath('D');

echo '<pre>';
print_r($res_D['path']);
echo '</pre>';

echo 'Shortest distance from A to D: '.$res_D['dist'];
```

`$d->calcDijkstra(u)` calculates the shortest path to every vertex from vertex `u`.
`$d->getPath(v)` returns an array `$res`, where `$res['path']` contains the shortest path and `$res['dist']` contains the distance of that path from the origin vertex `u` to the destination vertex `v`.

### Floyd-Warshall algorithm
The Floyd-Warshall algorithm calculates the shortest path between every single vertex.

To use the Floyd-Warshall algorithm, use the `GraphDS\Algo\FloydWarshall` class, and run `$fw = new FloydWarshall($g)`, where `$fw` is the Floyd-Warshall algorithm object, and `$g` is a graph.

After initializing the algorithm, the actual calculation of shortest paths in the graph can be invoked using `$fw->calcFloydWarshall()`.

Getting a path from the graph then is as easy as running `$res = $fw->getPath($u, $v)`, where `$u` and `$v` are two vertices.This returns an array of the path, `$res`, where `$res['path']` is the path, and `$res['distance']` is the distance of that path.

## Persistence
GraphDS has the ability to export and import graphs using the popular GraphML format. Note that for graph persistence to function correctly, the correct read/write permissions should be set on the server, which is beyond the scope of this README.

Examples of GraphML files are `graphUndirected.graphml` and `graphDirected.graphml`, both found in this repository.

### Exporting graphs
To export a graph to a GraphML file, use the `GraphDS\Persistence\ExportGraph` class, and run `$e = new ExportGraph($g)`, where `$e` is the graph exporter object, and `$g` is a graph. `$graphml = $e->getGraphML()` sets `$graphml` to the GraphML markup of the graph, and `$e->saveToFile($graphml, 'graph.graphml')` writes this markup to the file `graph.graphml`.

### Importing graphs
To import a graph from a GraphML file, use the `GraphDS\Persistence\ImportGraph` class, and run `$i = new ImportGraph()`, where `$i` is the graph importer object. `$g = $i->fromGraphML('graph.graphml')` sets `$g` to a GraphDS graph object represented by the GraphML markup in the file `graph.graphml`.

The object `$g` is now a conventional GraphDS, reconstructed from the GraphML markup in the file of `graph.graphml`.

## In case of bugs and/or suggestions
If, for any reason, there is a bug found in GraphDS, please message me on GitHub or send me an email to: <algb12.19@gmail.com>. The same goes for any suggestions.

Despite thorough unit testing, bugs will inevitably appear, so please open up any issues on GitHub if they arise!
