<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NumberController extends Controller
{
    public function classifyNumber(Request $request)
    {
        $number = $request->query('number');

        if (!is_numeric($number) || floor($number) != $number) {
            return response()->json([
                'number' => $number,
                'error' => true
            ], 400);
        }

        $number = (int) $number;

        $is_prime = $this->isPrime($number);
        $is_perfect = $this->isPerfect($number);
        $is_armstrong = $this->isArmstrong($number);
        $is_odd = $number % 2 !== 0;

        $properties = [];
        if ($is_armstrong) {
            $properties[] = 'armstrong';
        }
        $properties[] = $is_odd ? 'odd' : 'even';

        $digit_sum = array_sum(str_split((string) abs($number)));

        $fun_fact = $this->fetchFunFact($number);

        return response()->json([
            'number' => $number,
            'is_prime' => $is_prime,
            'is_perfect' => $is_perfect,
            'properties' => $properties,
            'digit_sum' => $digit_sum,
            'fun_fact' => $fun_fact
        ], 200);
    }

    private function isPrime($num)
    {
        if ($num < 2) return false;
        for ($i = 2; $i <= sqrt($num); $i++) {
            if ($num % $i == 0) return false;
        }
        return true;
    }

    private function isPerfect($num)
    {
        if ($num < 1) return false;
        $sum = 0;
        for ($i = 1; $i < $num; $i++) {
            if ($num % $i == 0) $sum += $i;
        }
        return $sum === $num;
    }

    private function isArmstrong($num)
    {
        $digits = str_split((string) abs($num));
        $power = count($digits);
        $sum = array_sum(array_map(fn($digit) => pow((int) $digit, $power), $digits));
        return $sum === $num;
    }

    private function fetchFunFact($num)
    {
        $response = Http::get("http://numbersapi.com/{$num}/math");
        return $response->successful() ? $response->body() : "No fun fact available.";
    }
}