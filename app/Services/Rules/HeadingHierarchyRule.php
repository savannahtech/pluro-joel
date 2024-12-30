<?php

namespace App\Services\Rules;

use DOMDocument;
use DOMXPath;

class HeadingHierarchyRule implements AccessibilityRuleInterface
{
    public function check(DOMDocument $dom): array
    {
        $issues = [];
        $xpath = new DOMXPath($dom);
        $headings = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');
        
        $previousLevel = 0;
        foreach ($headings as $heading) {
            $level = (int) substr($heading->nodeName, 1);
            
            if ($level - $previousLevel > 1) {
                $issues[] = [
                    'type' => 'warning',
                    'message' => "Skipped heading level: from h{$previousLevel} to h{$level}",
                    'element' => $heading->getNodePath()
                ];
            }
            
            $previousLevel = $level;
        }

        return $issues;
    }
}