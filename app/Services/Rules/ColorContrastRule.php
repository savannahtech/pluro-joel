<?php

namespace App\Services\Rules;

use DOMDocument;

class ColorContrastRule implements AccessibilityRuleInterface
{
    private const MIN_CONTRAST_RATIO = 4.5; // WCAG AA level for normal text

    public function check(DOMDocument $dom): array
    {
        $issues = [];
        $elements = $dom->getElementsByTagName('*');

        foreach ($elements as $element) {
            // Ensure the node is an instance of DOMElement
            if ($element instanceof \DOMElement) {
                if ($element->hasAttribute('style')) {
                    $style = $element->getAttribute('style');
                    $color = $this->extractStyleProperty($style, 'color');
                    $backgroundColor = $this->extractStyleProperty($style, 'background-color');

                    // Debugging: Log extracted styles
                    if (!$color || !$backgroundColor) {
                        $issues[] = [
                            'type' => 'warning',
                            'message' => sprintf(
                                'Unable to extract color or background-color from style: "%s"',
                                $style
                            ),
                            'element' => $element->getNodePath()
                        ];
                        continue;
                    }

                    $rgbColor = $this->hexToRgb($color);
                    $rgbBackgroundColor = $this->hexToRgb($backgroundColor);

                    // Debugging: Log color parsing results
                    if (!$rgbColor || !$rgbBackgroundColor) {
                        $issues[] = [
                            'type' => 'warning',
                            'message' => sprintf(
                                'Invalid color format. Color: "%s", Background-color: "%s"',
                                $color,
                                $backgroundColor
                            ),
                            'element' => $element->getNodePath()
                        ];
                        continue;
                    }

                    $contrastRatio = $this->calculateContrastRatio($rgbColor, $rgbBackgroundColor);

                    if ($contrastRatio < self::MIN_CONTRAST_RATIO) {
                        $issues[] = [
                            'type' => 'error',
                            'message' => sprintf(
                                'Insufficient color contrast (%.2f). Minimum required: %.1f',
                                $contrastRatio,
                                self::MIN_CONTRAST_RATIO
                            ),
                            'element' => $element->getNodePath()
                        ];
                    }
                }
            }
        }

        return $issues;
    }

    private function extractStyleProperty(string $style, string $property): ?string
    {
        if (preg_match('/' . preg_quote($property) . '\s*:\s*([^;]+);?/', $style, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function hexToRgb(string $color): ?array
    {
        // Handle named colors, rgb(), or fallback to hex
        if (preg_match('/^#?([a-f0-9]{3}|[a-f0-9]{6})$/i', $color, $matches)) {
            $hex = $matches[1];

            // Expand shorthand hex code
            if (strlen($hex) === 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }

            return [
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2)),
            ];
        } elseif (preg_match('/rgb\((\d+),\s*(\d+),\s*(\d+)\)/', $color, $matches)) {
            return [(int)$matches[1], (int)$matches[2], (int)$matches[3]];
        }

        return null;
    }

    private function calculateLuminance(array $rgb): float
    {
        $adjusted = array_map(function ($component) {
            $component /= 255;
            return $component <= 0.03928 ? $component / 12.92 : pow(($component + 0.055) / 1.055, 2.4);
        }, $rgb);

        // Relative luminance formula
        return 0.2126 * $adjusted[0] + 0.7152 * $adjusted[1] + 0.0722 * $adjusted[2];
    }

    private function calculateContrastRatio(array $rgb1, array $rgb2): float
    {
        $l1 = $this->calculateLuminance($rgb1);
        $l2 = $this->calculateLuminance($rgb2);

        // WCAG contrast ratio formula
        return ($l1 > $l2) ? ($l1 + 0.05) / ($l2 + 0.05) : ($l2 + 0.05) / ($l1 + 0.05);
    }
}
