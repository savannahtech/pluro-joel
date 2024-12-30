<?php

namespace App\Services\Rules;

class RuleRegistry
{
    private array $rules = [];

    public function __construct()
    {
        $this->registerRules();
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    private function registerRules(): void
    {
        $this->rules = [
            new ImageAltRule(),
            new HeadingHierarchyRule(),
            new LandmarkRule(),
            new AriaLabelRule(),
            new ColorContrastRule(),
            new FormLabelRule(),
        ];
    }
}