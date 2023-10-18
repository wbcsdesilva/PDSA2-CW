<?php

namespace App\Services;

// Longest Common Sequence Service
// -------------------------------

class LCSService
{

    function findLCS($str1, $str2)
    {
        $m = strlen($str1);
        $n = strlen($str2);

        // 2D array to store LCS lengths
        $dp = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));

        // filling the dp table using dynamic programming
        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                if ($str1[$i - 1] == $str2[$j - 1]) {
                    $dp[$i][$j] = $dp[$i - 1][$j - 1] + 1;
                } else {
                    $dp[$i][$j] = max($dp[$i - 1][$j], $dp[$i][$j - 1]);
                }
            }
        }

        // getting LCS from the dp table
        $lcs = '';
        $i = $m;
        $j = $n;

        // in reverse
        while ($i > 0 && $j > 0) {
            if ($str1[$i - 1] == $str2[$j - 1]) {
                $lcs = $str1[$i - 1] . $lcs;
                $i--;
                $j--;
            } elseif ($dp[$i - 1][$j] > $dp[$i][$j - 1]) {
                $i--;
            } else {
                $j--;
            }
        }

        return $lcs;
    }

    public function stringGenRandom($length = 10)
    {
        $characterPool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $maxIndex = strlen($characterPool) - 1;
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characterPool[random_int(0, $maxIndex)];
        }

        return $randomString;
    }
}
