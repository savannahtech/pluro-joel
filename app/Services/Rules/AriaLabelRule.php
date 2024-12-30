<?php

namespace App\Services\Rules;

use DOMDocument;
use DOMXPath;

class AriaLabelRule implements AccessibilityRuleInterface
{
    public function check(DOMDocument $dom): array
    {
        $issues = [];
        $xpath = new DOMXPath($dom);

        // XPath query to find interactive elements
        $elements = $xpath->query(
            '//*[@role="button" or @role="link" or self::button or self::a]'
        );

        foreach ($elements as $element) {
            // Ensure the node is a DOMElement
            if ($element instanceof \DOMElement) {
                // Check for aria-label, aria-labelledby, or non-empty text content
                $hasAriaLabel = $element->hasAttribute('aria-label');
                $hasAriaLabelledby = $element->hasAttribute('aria-labelledby');
                $hasTextContent = trim($element->textContent) !== '';

                if (!$hasAriaLabel && !$hasAriaLabelledby && !$hasTextContent) {
                    $issues[] = [
                        'type' => 'error',
                        'message' => 'Interactive element missing accessible name',
                        'element' => $element->getNodePath(),
                    ];
                }
            }
        }

        return $issues;
    }
}
