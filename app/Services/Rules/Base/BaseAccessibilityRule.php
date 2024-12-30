<?php

namespace App\Services\Rules\Base;

use App\Services\Rules\AccessibilityRuleInterface;
use App\Services\Rules\WCAGGuideline;
use DOMDocument;

abstract class BaseAccessibilityRule implements AccessibilityRuleInterface
{
    protected WCAGGuideline $guideline;

    abstract public function check(DOMDocument $dom): array;

    public function getGuideline(): WCAGGuideline
    {
        return $this->guideline;
    }

    protected function createIssue(string $type, string $message, string $element): array
    {
        return [
            'type' => $type,
            'message' => $message,
            'element' => $element,
            'wcagRef' => [
                'id' => $this->guideline->id,
                'url' => $this->guideline->url,
                'level' => $this->guideline->level->value
            ]
        ];
    }
}