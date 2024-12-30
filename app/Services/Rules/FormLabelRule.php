<?php

namespace App\Services\Rules;

use App\Services\Rules\Base\BaseAccessibilityRule;
use DOMDocument;

class FormLabelRule extends BaseAccessibilityRule
{
    public function __construct()
    {
        $this->guideline = new WCAGGuideline(
            '1.3.1',
            'Labels or Instructions',
            WCAGLevel::A,
            'https://www.w3.org/WAI/WCAG21/Understanding/labels-or-instructions'
        );
    }

    public function check(DOMDocument $dom): array
    {
        $issues = [];
        $xpath = new \DOMXPath($dom);

        $inputs = $xpath->query('//input[@type!="hidden"]|//select|//textarea');

        foreach ($inputs as $input) {
            if ($input instanceof \DOMElement) {
                if (
                    !$input->hasAttribute('id') ||
                    !$xpath->query("//label[@for='{$input->getAttribute('id')}']")->length
                ) {
                    $issues[] = $this->createIssue(
                        'error',
                        'Form control missing associated label',
                        $input->getNodePath()
                    );
                }
            }
        }

        return $issues;
    }
}
