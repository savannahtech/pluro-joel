<?php

namespace App\Services\Rules;

use DOMDocument;

interface AccessibilityRuleInterface
{
    public function check(DOMDocument $dom): array;
}