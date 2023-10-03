<?php

namespace App\Http\Controllers;

use App\Services\ShortestPathService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        try {

            // Validate
            $request->validate([
                'cityDistances' => 'required',
                'cityPaths' => 'required',
            ]);

            $startCity = $request->input('startCity');
            $graph = $request->input('distanceGraph');

            $cityDistances = $request->input('cityDistances');
            $cityPaths = $request->input('cityPaths');

            $this->shortestPathService->setGraph($graph);

            $correctSolution = $this->findShortestPath($startCity);

            $allDistancesCorrect = $this->compareCityDistances($cityDistances, $correctSolution[0]);
            $allPathsCorrect = $this->compareCityPaths($cityPaths, $correctSolution[1]);

            if ($allDistancesCorrect && $allPathsCorrect) {
                return response()->json(['solutionIsCorrect' => true], 200);
            } else {
                return response()->json(['solutionIsCorrect' => false], 200);
            }
        } catch (ValidationException $e) {
            return response()->json(['solutionIsInvalid' => true, 'message' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function submitSolution(Request $request)
    {
        try {

            return response()->json(['message' => 'Solution submission successful'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function findShortestPath($startCity)
    {

        $dijkstraShortestPath = $this->shortestPathService->dijkstraShortestPath($startCity);

        $bellmanFordShortestPath = $this->shortestPathService->bellmanFordShortestPath($startCity);

        $graph = $this->shortestPathService->getGraph();

        return response()->json([
            'start_city' => $startCity,
            'dijkstra_shortest_path' => $dijkstraShortestPath,
            'bellman_ford_shortest_path' => $bellmanFordShortestPath,
        ]);
    }
}
