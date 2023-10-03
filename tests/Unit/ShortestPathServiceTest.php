<?php

namespace Tests\Unit;

use App\Services\ShortestPathService;
use PHPUnit\Framework\TestCase;

class ShortestPathServiceTest extends TestCase
{
    public function testDijkstraShortestPath()
    {
        $service = new ShortestPathService();
        $result = $service->dijkstraShortestPath('A');

        // Checking if the returns an array
        $this->assertIsArray($result);

        // Making sure all the distances are calculated
        $this->assertArrayHasKey('distances', $result);
        $this->assertIsArray($result['distances']);

        // Making sure all the paths are calculated
        $this->assertArrayHasKey('paths', $result);
        $this->assertIsArray($result['paths']);

        // Ensure the execution time is calculated
        $this->assertArrayHasKey('execution_time', $result);
        $this->assertIsFloat($result['execution_time']);
    }

    public function testBellmanFordShortestPath()
    {
        $service = new ShortestPathService();
        $result = $service->bellmanFordShortestPath('A');

        // Checking if the returns an array
        $this->assertIsArray($result);

        // Making sure all the distances are calculated
        $this->assertArrayHasKey('distances', $result);
        $this->assertIsArray($result['distances']);

        // Making sure all the paths are calculated
        $this->assertArrayHasKey('paths', $result);
        $this->assertIsArray($result['paths']);

        // Ensure the execution time is calculated
        $this->assertArrayHasKey('execution_time', $result);
        $this->assertIsFloat($result['execution_time']);
    }

    public function testRandomStartCityValidity()
    {
        $service = new ShortestPathService();
        $randomCity = $service->pickRandomStartCity();

        // Checking if the random city is among the cities in our set
        $this->assertContains($randomCity, ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J']);
    }
}
