<?php

namespace App\Services\Rules;

use App\Services\Rules\Base\BaseAccessibilityRule;
use DOMDocument;

class LandmarkRule extends BaseAccessibilityRule
{
    public function __construct()
    {
        $this->guideline = new WCAGGuideline(
            '1.3.1',
            'Info and Relationships',
            WCAGLevel::A,
            'https://www.w3.org/WAI/WCAG21/Understanding/info-and-relationships'
        );
    }

    public function check(DOMDocument $dom): array
    {
        $issues = [];
        $xpath = new \DOMXPath($dom);
        
        // Check for main landmark
        $main = $xpath->query('//main');
        if ($main->length === 0) {
            $issues[] = $this->createIssue(
                'warning',
                'No <main> landmark found - consider adding one for better document structure',
                '/html/body'
            );
        }

        // Check for navigation landmark
        $nav = $xpath->query('//nav');
        if ($nav->length === 0) {
            $issues[] = $this->createIssue(
                'warning',
                'No <nav> landmark found - consider adding one for navigation sections',
                '/html/body'
            );
        }

        return $issues;
    }
}