<?php

namespace App\Services;

use App\Services\Rules\RuleRegistry;
use App\Services\Scoring\AccessibilityScoreCalculator;
use DOMDocument;

class AccessibilityAnalyzer
{
    public function __construct(
        private readonly RuleRegistry $ruleRegistry,
        private readonly AccessibilityScoreCalculator $scoreCalculator
    ) {}

    public function analyze(string $content): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($content, LIBXML_NOERROR);

        $issues = [];
        foreach ($this->ruleRegistry->getRules() as $rule) {
            $issues = array_merge($issues, $rule->check($dom));
        }

        return [
            'score' => $this->scoreCalculator->calculate($issues),
            'issues' => $issues,
            'summary' => $this->generateSummary($issues)
        ];
    }

    private function generateSummary(array $issues): array
    {
        $errorCount = count(array_filter($issues, fn($i) => $i['type'] === 'error'));
        $warningCount = count(array_filter($issues, fn($i) => $i['type'] === 'warning'));

        return [
            'totalIssues' => count($issues),
            'errors' => $errorCount,
            'warnings' => $warningCount
        ];
    }
}