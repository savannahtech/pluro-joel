<?php

namespace App\Services\Scoring;

class AccessibilityScoreCalculator
{
    private const BASE_SCORE = 100;
    private const ERROR_PENALTY = 10;
    private const WARNING_PENALTY = 5;

    public function calculate(array $issues): int
    {
        $penalties = array_reduce($issues, function ($total, $issue) {
            return $total + ($issue['type'] === 'error' ? self::ERROR_PENALTY : self::WARNING_PENALTY);
        }, 0);

        return max(0, self::BASE_SCORE - $penalties);
    }
}