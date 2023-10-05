<?php

namespace App\Services;

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
        $previousNodes = []; // for path tracking
        $visited = [];
        $cities = array_keys($this->graph);

        foreach ($cities as $city) {
            $distances[$city] = INF;
            $visited[$city] = false;
        }

        $distances[$startCity] = 0;

        $start_time = microtime(true);

        for ($i = 0; $i < count($cities); $i++) {
            $minDistance = INF;
            $currentCity = null;

            foreach ($cities as $city) {
                if (!$visited[$city] && $distances[$city] < $minDistance) {
                    $minDistance = $distances[$city];
                    $currentCity = $city;
                }
            }

            if ($currentCity === null) {
                break;
            }

            $visited[$currentCity] = true;

            foreach ($this->graph[$currentCity] as $neighbor => $weight) {
                if (!$visited[$neighbor] && $distances[$currentCity] + $weight < $distances[$neighbor]) {
                    $distances[$neighbor] = $distances[$currentCity] + $weight;
                    $previousNodes[$neighbor] = $currentCity;
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
