<?php

namespace App\Services;

use SplPriorityQueue;

// Shortest Path Service Service
// -----------------------------

class ShortestPathService
{
    protected $graph;

    public function __construct()
    {
        $this->initializeGraph();
    }

    private function initializeGraph()
    {
        $cities = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $cityCount = count($cities);

        // Adjency list representation :
        for ($i = 0; $i < $cityCount; $i++) {
            for ($j = $i + 1; $j < $cityCount; $j++) {

                $distance = rand(5, 50);

                $this->graph[$cities[$i]][$cities[$j]] = $distance;
                $this->graph[$cities[$j]][$cities[$i]] = $distance;

                // Assigning both ways cause undirected
            }
        }
    }

    public function getGraph()
    {
        return $this->graph;
    }

    public function setGraph($graph)
    {
        return $this->graph = $graph;
    }


    public function dijkstraShortestPath($startCity)
    {
        $distances = [];
        $previousNodes = [];
        $pq = new SplPriorityQueue();
        $cities = array_keys($this->graph);

        foreach ($cities as $city) {
            $distances[$city] = INF;
            $previousNodes[$city] = null;
        }

        $distances[$startCity] = 0;
        $pq->insert(new Node($startCity, 0), 0);

        $start_time = microtime(true);

        while (!$pq->isEmpty()) {
            $currentNode = $pq->extract();
            $currentCity = $currentNode->city;
            $currentDistance = $currentNode->distance;

            if ($currentDistance > $distances[$currentCity]) {
                continue;
            }

            foreach ($this->graph[$currentCity] as $neighbor => $weight) {
                $newDistance = $currentDistance + $weight;
                if ($newDistance < $distances[$neighbor]) {
                    $distances[$neighbor] = $newDistance;
                    $previousNodes[$neighbor] = $currentCity;
                    $pq->insert(new Node($neighbor, $newDistance), -$newDistance);
                }
            }
        }

        // Construct paths
        $paths = [];
        foreach ($cities as $destinationCity) {
            $path = $destinationCity;
            $currentCity = $destinationCity;

            while ($previousNodes[$currentCity] !== null) {
                $path = $previousNodes[$currentCity] . '->' . $path;
                $currentCity = $previousNodes[$currentCity];
            }

            $paths[$destinationCity] = $path;
        }

        $end_time = microtime(true);
        $execution_time_ms = ($end_time - $start_time) * 1000;

        return ['distances' => $distances, 'paths' => $paths, 'execution_time' => round($execution_time_ms, 5)];
    }

    public function bellmanFordShortestPath($startCity)
    {
        $distances = [];
        $previousNodes = []; // for path tracking
        $cities = array_keys($this->graph);

        foreach ($cities as $city) {
            $distances[$city] = INF;
        }

        $distances[$startCity] = 0;

        $start_time = microtime(true);

        for ($i = 0; $i < count($cities) - 1; $i++) {
            foreach ($cities as $city) {
                foreach ($this->graph[$city] as $neighbor => $weight) {
                    if ($distances[$city] + $weight < $distances[$neighbor]) {
                        $distances[$neighbor] = $distances[$city] + $weight;
                        $previousNodes[$neighbor] = $city;
                    }
                }
            }
        }

        // Construct paths
        $paths = [];
        foreach ($cities as $destinationCity) {
            $path = $destinationCity;
            $currentCity = $destinationCity;

            while (isset($previousNodes[$currentCity])) {
                $path = $previousNodes[$currentCity] . '->' . $path;
                $currentCity = $previousNodes[$currentCity];
            }

            $paths[$destinationCity] = $path;
        }

        $end_time = microtime(true);
        $execution_time_ms = ($end_time - $start_time) * 1000;

        return ['distances' => $distances, 'paths' => $paths, 'execution_time' => round($execution_time_ms, 5)];
    }

    public function pickRandomStartCity()
    {
        $cities = array_keys($this->graph);
        $randomIndex = array_rand($cities);

        return $cities[$randomIndex];
    }
}

// node class : for dijikstra's use

class Node
{
    public $city;
    public $distance;

    public function __construct($city, $distance)
    {
        $this->city = $city;
        $this->distance = $distance;
    }
}
