<?php

namespace Tests\Unit;

use App\Services\LCSService;
use PHPUnit\Framework\TestCase;

class LCSServiceTest extends TestCase
{

    public function testFindLCS()
    {
        $lcsService = new LCSService();

        // Test case where both strings are equal
        $str1 = 'ABCD';
        $str2 = 'ABCD';
        $expectedLCS = 'ABCD';
        $this->assertEquals($expectedLCS, $lcsService->findLCS($str1, $str2));

        // Test case with different characters
        $str1 = 'ABC';
        $str2 = 'DEF';
        $expectedLCS = '';
        $this->assertEquals($expectedLCS, $lcsService->findLCS($str1, $str2));

        // Test case with partial match
        $str1 = 'ABCD';
        $str2 = 'ACDF';
        $expectedLCS = 'ACD';
        $this->assertEquals($expectedLCS, $lcsService->findLCS($str1, $str2));
    }

    public function testRandomStringGeneration()
    {
        $lcsService = new LCSService();

        // Test random string generation
        $length = 5;
        $randomString = $lcsService->stringGenRandom($length);
        $this->assertEquals($length, strlen($randomString));
    }
}
