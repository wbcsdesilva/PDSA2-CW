<?php

namespace App\Http\Controllers;

use App\Services\ShortestPathService;
use Illuminate\Http\Request;

class ShortestPathController extends Controller
{
    protected $shortestPathService;

    public function __construct(ShortestPathService $shortestPathService)
    {
        $this->shortestPathService = $shortestPathService;
    }

    public function index()
    {
        $startCity = $this->shortestPathService->pickRandomStartCity();
        $distanceGraph = $this->shortestPathService->getGraph();

        return view('shortest-path', compact('distanceGraph', 'startCity'));
    }


    public function assessSolution(Request $request)
    {
        $startCity = $request->input('startCity');
        $graph = $request->input('distanceGraph');

        // FIXME: Same issue as the tic-tac-toe board. Find out why the graph is getting re-initialized
        // Current workaround: Getting and setting the graph from the front end again
        $this->shortestPathService->setGraph($graph);

        $this->findShortestPath($startCity);
    }

    public function submitSolution(Request $request)
    {
    }

    private function findShortestPath($startCity)
    {

        $dijkstraShortestPath = $this->shortestPathService->dijkstraShortestPath($startCity);

        $bellmanFordShortestPath = $this->shortestPathService->bellmanFordShortestPath($startCity);

        $graph = $this->shortestPathService->getGraph();

        // The algorithms are working properly,
        // The issue is the graphs being mismatching, just like from tic-tac-toe
        // take the graph from the front-end and put it here

        dd($startCity, $dijkstraShortestPath, $bellmanFordShortestPath, $graph);

        return response()->json([
            'start_city' => $startCity,
            'dijkstra_shortest_path' => $dijkstraShortestPath,
            'bellman_ford_shortest_path' => $bellmanFordShortestPath,
        ]);
    }
}
