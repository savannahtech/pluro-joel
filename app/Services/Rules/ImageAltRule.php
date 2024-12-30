<?php

namespace App\Services\Rules;

use App\Services\Rules\Base\BaseAccessibilityRule;
use DOMDocument;
use DOMElement;

class ImageAltRule extends BaseAccessibilityRule
{
    public function __construct()
    {
        $this->guideline = new WCAGGuideline(
            '1.1.1',
            'Non-text Content',
            WCAGLevel::A,
            'https://www.w3.org/WAI/WCAG21/Understanding/non-text-content'
        );
    }

    public function check(DOMDocument $dom): array
    {
        $issues = [];
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            // Ensure the node is a DOMElement
            if ($img instanceof DOMElement) {
                if (!$img->hasAttribute('alt')) {
                    $issues[] = $this->createIssue(
                        'error',
                        'Image missing alt attribute',
                        $img->getNodePath() ?: 'unknown'
                    );
                } elseif (trim($img->getAttribute('alt')) === '') {
                    $issues[] = $this->createIssue(
                        'warning',
                        'Image has empty alt attribute - ensure this is intentional for decorative images',
                        $img->getNodePath() ?: 'unknown'
                    );
                }
            }
        }

        return $issues;
    }
}
