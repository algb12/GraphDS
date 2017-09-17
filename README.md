# GraphDS
[![Code Climate](https://codeclimate.com/github/algb12/GraphDS/badges/gpa.svg)](https://codeclimate.com/github/algb12/GraphDS)

## What is GraphDS and why was it created?
GraphDS is an object-oriented, lightweight implementation of the graph data-structure in PHP.

In a project of mine, I needed a way to represent graphs in PHP. None of the existing solutions suited me, so I have decided to write my own graph library from scratch. The original implementation used in my project contains additional functions for graph traversal and refactoring of graphs, but these functions are specific to my project.

This version of GraphDS is a toned-down version of my original implementation. It makes use of OOP practices to allow algorithms to be loaded only on demand, making GraphDS fast, extendable and lightweight at the same time.

GraphDS requires at least PHP version 5.3. Unit tests are run from PHP 5.4 onwards.

## How to install
Simply require the Composer package. In the directory of your project, run:

`composer require algb12/graph-ds`

## What is it even useful for?
Please see the sample app in the `SampleApp_RoadPlanner` directory to find a primitive application of GraphDS. The RoadPlanner app calculates the shortest road between two cities, using Dijkstra's, multi-path Dijkstra's and the Floyd-Warshall algorithm. It also shows the order of vertex discoveries by the BFS and DFS algorithms.

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

### Transpose graphs
The _transpose graph_ of a directed graph is when all edges `(u, v)` become `(v, u)`, where `u` and `v` are vertices connected by an edge.

To get the transpose of a directed graph `$g` as a `DirectedGraph` object, call:

`$g->getTranspose()`

This may be useful for algorithms which require graph transposition, and now, GraphDS provides a method to achieve it.

### Vertices
In any graph, all vertices can be accessed through the `$g->vertices` array, where `$g` is an instance of a graph object. So, to access vertex `A`, the syntax would be `$g->vertices['A']`.

#### Adding and removing vertices
Adding and removing vertices can be accomplished using the `$g->addVertex('v')` and `$g->removeVertex('v')` methods, where `v` is the name of the vertex to be added/removed.

#### Getting and setting the value of a vertex
To get the value of a vertex, `$g->vertices['v']->getValue()` can be called. To set the value of a vertex, `$g->vertices['v']->setValue(value)` can be called, where `value` is stored as a string.

#### Getting neighbors of a vertex
Getting the neighbors of a vertex in an undirected graph can be accomplished by using `$g->vertices['v']->getNeighbors()`.

In a directed graph, `$g->vertices['v']->getInNeighbors` returns an array of all vertices connected by an incoming edge, whereas `$g->vertices['v']->getOutNeighbors()` returns an array of all vertices connected by an outgoing edge to the current vertex. `$g->vertices['v']->getNeighbors()` returns an array of two subarrays, `in` and `out` for incoming and outgoing vertices.

#### Indegrees and Outdegrees
The indegree and outdegree of a vertex is simply how many incoming and outgoing vertices are connected to a vertex, respectively.

The indegree and outdegree of a vertex can be easily determined using `$g->vertices['v']->getIndegree()` and `$g->vertices['v']->getOutdegree()`.

#### Asserting vertex adjacency
In any graph, asserting that vertex `B` is adjacent to `A` can be done by calling `$g->vertices['A']->adjacent('B')`. Note that in a directed graph, a vertex will be considered as adjacent using the `adjacent` method, no matter whether it is incoming or outgoing.

In a directed graph, inward and outward adjacency can be asserted by using `$g->vertices['A']->inAdjacent('B')` and `$g->vertices['A']->outAdjacent('B')`, respectively.

### Edges
Edges are the objects that connect vertices together. Note that in GraphDS, edges are _not actual_ connections between vertices, but merely _abstract_ connections, meaning that they are stored as separate objects to the vertices.

Edges can be accessed easily via `$g->edge('A', 'B')`, where `A` and `B` are the vertices connected by the edge. Note that in an undirected graph, `$g->edge('A', 'B')` and `$g->edge('B', 'A')` are equivalent, whereas in directed graphs, they represent 2 distinct edges.

#### Real and virtual edges
In GraphDS, edges are stored as object, and each object takes up space. To reduce the spatial footprint, the `edge` function was introduced in version 1.0.3, which returns both, real edges and "virtual" edges.

Real edges are actual `DirectedEdge` or `UndirectedEdge` objects, whereas virtual edges are _not_ actually edge objects, but merely a result of the `edge` function returning edge `('A', 'B')`, even when `('B', 'A')` is requested. A virtual edge can be modified just like a real edge, with the exception of being removed. Virtual edges are not part of directed graphs.

This is desired behavior, as in undirected graphs, edge `('A', 'B')` would be equivalent to `('B', 'A')`. Virtual edges also eliminate the problem of edge duplication in undirected graphs, and therefore also reduce the memory GraphDS takes up.

#### Adding and removing edges
To add an edge, simply call `$g->addEdge('A', 'B', 'value')`, where `A` and `B` are the vertices in graph `$g` to be connected by this edge, and `value` an optional value for the edge, which must be numeric. The default value of edges is `null`.

Note that in undirected graphs, this also adds a "virtual" edge `('B', 'A')` in addition to `('A', 'B')`.

Removing an edge can be accomplished via `$g->removeEdge('A', 'B')`. Due to the behavior of virtual edges, this will remove both, the real edge `('A', 'B')` and the virtual edge `('B', 'A')` in an undirected graph, but only the real edge in a directed graph.

#### Getting and setting the value of an edge
To get the value of an edge, `$g->edge('A', 'B')->getValue()` can be called. To set the value of an edge, `$g->edge('A', 'B')->setValue(value)` can be called, where `value` can be any storable data-type.

## Algorithms
Since version 1.0.1, GraphDS has support for algorithms. `GraphDS\Algo` is the namespace of algorithms in GraphDS.

In GraphDS, algorithms are treated as separate objects modifying the graph. They accept the graph and work on it, but do not impact the graph's core functionality.

This is what makes GraphDS lean and streamlined, as algorithms only have to be loaded into memory if they are needed, as they are not intrinsic to a graph object.

### Running algorithms
1. Any algorithm first has to be "used" by PHP, e.g. `use GraphDS\Algo\Algorithm`
2. An new instance of the algorithm on the relevant graph (`$g`) has to be created, e.g. `$a = new Algorithm($g)`
3. The algorithm can now be run using `$a->run(args)`, where `args` are arguments which differ from algorithm to algorithm
4. Getting the results of an algorithm is done using `$a->get(args)`, where args are arguments which differ from algorithm to algorithm

The "run-and-get" pragma eases the use of algorithms, as these are the only two public methods an algorithm should have. From now on, the documentation will only refer to the arguments the algorithm methods accept.

### Writing GraphDS algorithms
In order to ease the writing of algorithms, PHP's Standard PHP Library (SPL) provides useful helper classes, such as:
- SplQueue: An implementation of a FIFO queue
- SplStack: An implementation of a LIFO stack

These classes greatly simplify the writing of algorithms, such as depth-first search (DFS) and breadth-first search (BFS), which can be found in the directory with the algorithms.

The following outlines the basic structure of GraphDS algorithms. For the sake of brevity, docblocks have been left out, although it is most recommended to properly document the algorithm:

```php
<?php
namespace GraphDS\Algo;

use InvalidArgumentException;

// Class defining an algorithm named "Algorithm"
class Algorithm
{
    // Reference to the graph
    public $graph;

    // Any global variables...

    // Constructor accepts a GraphDS graph and validates it
    public function __construct($graph)
    {
        if (empty($graph) || get_parent_class($graph)  !== 'GraphDS\Graph\Graph') {
            throw new InvalidArgumentException("Algorithm requires a graph.");
        }
        $this->graph = &$graph;
    }

    // Running the algorithm
    public function run(args)
    {
        code...
    }

    // Getting the results of the algorithm
    public function get(args)
    {
        code...
    }

    // Any private helper methods...
}
```

The above, outlined in words again:
- Check if graph is actually a graph and use it, otherwise throw `InvalidArgumentException`
- `run(args)` executes the algorithm, where `args` are arguments
- `get(args)` gets the results of the algorithm, where `args` are arguments

### Breadth-first search (BFS)
BFS, a path traversal algorithm, is in the class `GraphDS\Algo\BFS`. It visits every vertex in the graph, and goes along the breadth of the graph. As such, level by level, it visits every vertex in the graph.

- `$bfs->run($root)` accepts `$root` as a compulsory argument, this is the starting vertex for the BFS
- `$bfs->get()` accepts no arguments. It returns an array `$arr`, with subarrays:
  - `$arr['discovered']` (vertices discovered in BFS order)
  - `$arr['dist']` (the distances of each vertex to the root vertex, in hops)
  - `$arr['parent']`, each vertex's parent vertex when using BFS

### Depth-first search (DFS)
DFS, a path traversal algorithm, is in the class `GraphDS\Algo\DFS`. It visits every vertex in the graph, and goes along the depth of the graph. As such, it visits every vertex in the graph, and only move from vertex to vertex once the vertex has been visited to its full depth.

- `$dfs->run($root)` accepts `$root` as a compulsory argument, this is the starting vertex for the DFS
- `$dfs->get()` accepts no arguments. It returns an array `$arr`, with subarrays:
  - `$arr['discovered']` (vertices discovered in DFS order)
  - `$arr['dist']` (the distances of each vertex to the root vertex, in hops)
  - `$arr['parent']`, each vertex's parent vertex when using DFS

### Dijkstra's shortest path algorithm
Dijkstra's shortest path algorithm finds the shortest path between a vertex and all other vertices. It is in the class `GraphDS\Algo\Dijkstra`.

- `$dijkstra->run($start)` accepts `$start` as a compulsory argument, this is the vertex from which Dijkstra should start
- `$dijkstra->get($dest)` accepts `$dest` as a compulsory argument, which is the destination vertex to which the shortest path should be returned. It returns an array `$arr`, with subarrays:
  - `$arr['path']` (the shortest path to the vertex `$dest` from `$start`)
  - `$arr['dist']` (the shortest distances of each vertex to the root vertex, in edge weights)

### Multi-path Dijkstra's shortest path algorithm
Unlike the single-path version, the multi-path Dijkstra's shortest path algorithm finds _all_ the shortest path between a vertex and all other vertices. It is in the class `GraphDS\Algo\DijkstraMulti`.

- `$dijkstra_mult->run($start)` accepts `$start` as a compulsory argument, this is the vertex from which Dijkstra should start
- `$dijkstra_mult->get($dest)` accepts `$dest` as a compulsory argument, which is the destination vertex to which all the shortest paths should be returned. It returns an array `$arr`, with subarrays:
  - `$arr['paths']` (an array of all the shortest paths to the vertex `$dest` from `$start`)
  - `$arr['dist']` (the shortest distances of each vertex to the root vertex, in edge weights)

### Floyd-Warshall algorithm
The Floyd-Warshall algorithm calculates the shortest path between every single vertex in the graph. It is in the class `GraphDS\Algo\FloydWarshall`.

- `$fw->run()` accepts no arguments, and simply runs then algorithm on the graph
- `$fw->get($startVertex, $destVertex)` accepts `$startVertex` and `$destVertex` as a compulsory argument, which are the start vertex and the destination vertex, respectively, between which the shortest path should be worked out. It returns an array `$arr`, with subarrays:
  - `$arr['path']` (the shortest path to the vertex `$dest` from `$start`)
  - `$arr['dist']` (the shortest distance of the destination vertex to the start vertex, in edge weights)

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

Despite thorough unit testing, bugs will inevitably appear, so please open up any issues on GitHub if they arise! Currently, PHP 5.3 and onwards is supported, although at least PHP 5.6 is recommended.
