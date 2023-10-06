<?php

namespace App\Http\Controllers;

use App\Models\ShortestPathPlayerSubmission;
use App\Services\ShortestPathService;
use Exception;
use Illuminate\Database\QueryException;
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

            $startCity = $request->input('startCity');
            $graph = $request->input('distanceGraph');

            $cityDistances = $request->input('cityDistances');
            $cityPaths = $request->input('cityPaths');

            $this->shortestPathService->setGraph($graph);

            // find the shortest path using both algorithms
            $correctSolution = $this->findShortestPath($startCity);

            $allDistancesCorrect = $this->compareCityDistances($cityDistances, $correctSolution['dijkstra_shortest_path']['distances']);
            $allPathsCorrect = $this->compareCityPaths($cityPaths, $correctSolution['dijkstra_shortest_path']['paths']);


            if ($allDistancesCorrect && $allPathsCorrect) {

                // set the execution times into the session
                $request->session()->put('dijkstraTime',  $correctSolution['dijkstra_shortest_path']['execution_time']);
                $request->session()->put('bellmanFordTime', $correctSolution['bellman_ford_shortest_path']['execution_time']);

                return response()->json(['solutionIsCorrect' => true], 200);
            } else {

                return response()->json(['solutionIsCorrect' => false, 'correctSolution' => $correctSolution['dijkstra_shortest_path']], 200);
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

            $request->validate([
                'playerName' => 'required|string|max:255|regex:/^[A-Za-z0-9_]+$/',
            ]);

            // game data
            $playerName = $request->input('playerName');
            $startCity = $request->input('startCity');
            $cityDistances = $request->input('cityDistances');
            $cityPaths = $request->input('cityPaths');

            $dijkstraTime =  $request->session()->get('dijkstraTime');
            $bellmanFordTime = $request->session()->get('bellmanFordTime');

            $submissionData = [
                'player_name' => $playerName,
                'start_city' => $startCity,
                'dijkstra_time' =>  $dijkstraTime,
                'bellman_ford_time' => $bellmanFordTime,
            ];


            // Populate distances and paths for each city (A to J)
            foreach (range('A', 'J') as $city) {
                $submissionData["distance_to_$city"] = $cityDistances[$city] ?? null;
                $submissionData["path_to_$city"] = $cityPaths[$city] ?? null;
            }

            ShortestPathPlayerSubmission::create($submissionData);

            return response()->json(['message' => 'Solution submission successful'], 200);
        } catch (ValidationException $e) {
            return response()->json(['type' => 'VALIDATION_EXCEPTION', 'message' => $e->getMessage()], 400);
        } catch (QueryException $e) {
            return response()->json(['type' => 'QUERY_EXCEPTION', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['type' => 'GENERAL_EXCEPTION', 'message' => $e->getMessage()], 500);
        }
    }

    private function findShortestPath($startCity)
    {

        $dijkstraShortestPath = $this->shortestPathService->dijkstraShortestPath($startCity);

        $bellmanFordShortestPath = $this->shortestPathService->bellmanFordShortestPath($startCity);

        return [
            'dijkstra_shortest_path' => $dijkstraShortestPath,
            'bellman_ford_shortest_path' => $bellmanFordShortestPath,
        ];
    }


    function compareCityDistances($userCityDistances, $correctCityDistances)
    {
        // validate distances before you continue
        $this->validateDistances($userCityDistances);

        $allDistancesCorrect = true;

        foreach ($correctCityDistances as $city => $distance) {
            if (!isset($userCityDistances[$city]) || $userCityDistances[$city] !== $distance) {
                $allDistancesCorrect = false;
                break;
            }
        }

        return $allDistancesCorrect;
    }


    function compareCityPaths($userCityPaths, $correctCityPaths)
    {
        // validate city paths before you do anything
        $this->validatePaths($userCityPaths);

        $allPathsCorrect = true;

        foreach ($correctCityPaths as $city => $correctPath) {
            if ($userCityPaths[$city] !== $correctPath) {
                $allPathsCorrect = false;
                break;
            }
        }

        return $allPathsCorrect;
    }

    // valdiates paths
    function validatePaths($paths)
    {
        foreach ($paths as $city => $path) {
            if ($path === null) {
                throw ValidationException::withMessages([
                    'message' => ['Paths missing! Plese provide a path to the city ' . $city],
                ]);
            } elseif (preg_match('/^[A-Z](->[A-Z])*$/', $path) !== 1) {
                throw ValidationException::withMessages([
                    'message' => ['Invalid paths found! The path to the city ' . $city . ' should match the City->City format.'],
                ]);
            }
        }
    }

    // validate distances
    function validateDistances($distances)
    {
        foreach ($distances as $city => $distance) {
            if ($distance === null) {
                throw ValidationException::withMessages([
                    'message' => ['Distance for city ' . $city . ' is empty. Please provide a valid distance.'],
                ]);
            }

            if (!is_numeric($distance) || $distance < 0 || !ctype_digit((string) $distance)) {
                throw ValidationException::withMessages([
                    'message' => ['Invalid distance for city ' . $city . '. Distance should be a non-negative integer.'],
                ]);
            }
        }
    }
}
