# GraphDS
A fast implementation of the graph data-structure in PHP

## Why was GraphDS created?
In a project of mine, I needed a way to represent graphs in PHP. None of the existing solutions have suited me, so I have decided to write my own graph library from scratch. The original implementation used in my project contains additional functions for graph traversal and refactoring of graphs, but these functions are specific to my project.

This version of GraphDS is a toned-down version of my original implementation. It is easily extendable by extending the `UndirectedGraph`, `DirectedGraph` or `Graph` class.

## How to install
Simply require the Composer package. In the directory of your project, run:

`composer require algb12/graph-ds`

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
To get the value of a vertex, `$g->vertices['v']->getValue()` can be called. To set the value of a vertex, `$g->vertices['v']->setValue(value)` can be called, where `value` can be any storable data-type.

#### Getting neighbors of a vertex
Getting the neighbors of a vertex in an undirected graph can be accomplished by using `$g->vertices['v']->getNeighbors()`.

In a directed graph, `$g->vertices['v']->getInNeighbors` returns an array of all vertices connected by an incoming edge, whereas `$g->vertices['v']->getOutNeighbors` returns an array of all vertices connected by an outgoing edge to the current vertex. `$g->vertices['v']->getNeighbors()` returns an array of two subarrays, `in` and `out` for incoming and outgoing vertices.

#### Indegrees and Outdegrees
The indegree and outdegree of a vertex is simply how many incoming and outgoing vertices are connected to a vertex, respectively.

The indegree and outdegree of a vertex can be easily determined using `$g->vertices['v']->getIndegree` and `$g->vertices['v']->getOutdegree`.

#### Asserting vertex adjacency
In any graph, asserting that vertex `v2` is adjacent to `v1` can be done by calling `$g->vertices['v1']->adjacent('v2')`. Note that in a directed graph, a vertex will be considered as adjacent using the `adjacent` method, no matter whether it is incoming or outgoing.

In a directed graph, inward and outward adjacency can be asserted by using `$g->vertices['v1']->inAdjacent('v2')` and `$g->vertices['v1']->outAdjacent('v2')`, respectively.

### Edges
Edges are the objects that connect vertices together. Note that in GraphDS, edges are _not actual_ connections between vertices, but merely _abstract_ connections, meaning that they are stored as separate objects to the vertices.

Accessing an edge can easily be done with `$g->edges['v1']['v2']`, where `v1` and `v2` are the vertices connected by the edge. Note that in an undirected graph, `$g->edges['v1']['v2']` and `$g->edges['v2']['v1']` are equivalent, whereas in directed graphs, they represent 2 distinct edges.

#### Adding and removing edges
To add an edge, simply call `$g->addEdge('v1', 'v2')`, where `v1` and `v2` are the vertices to be connected by this edge.

Note that in undirected graphs, this also adds edge `['v2']['v1']` in addition to `['v1']['v2']`. The fact that both 'directions' for the edge are affected goes for any method concerning edges.

#### Getting and setting the value of an edge
To get the value of an edge, `$g->edges['v1']['v2']->getValue()` can be called. To set the value of an edge, `$g->edges['v1']['v2']->setValue(value)` can be called, where `value` can be any storable data-type.

## In case of bugs and/or suggestions
If, for any reason, there is a bug found in GraphDS, please message me on GitHub or send me an email to: <algb12.19@gmail.com>. The same goes for any suggestions. Please open up any issues if they arise!
