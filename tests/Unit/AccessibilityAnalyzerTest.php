<?php

namespace Tests\Unit;

use App\Services\AccessibilityAnalyzer;
use App\Services\Rules\RuleRegistry;
use App\Services\Scoring\AccessibilityScoreCalculator;
use PHPUnit\Framework\TestCase;
use Mockery;

class AccessibilityAnalyzerTest extends TestCase
{
    public function test_calculates_correct_score()
    {
        // Mock the RuleRegistry
        /** @var RuleRegistry|Mockery\MockInterface $ruleRegistryMock */
        $ruleRegistryMock = Mockery::mock(RuleRegistry::class);

        // Mock the Rule object (the object that would be returned by getRules())
        /** @var \App\Services\Rules\Rule|Mockery\MockInterface $ruleMock */
        $ruleMock = Mockery::mock('App\Services\Rules\Rule');
        $ruleMock->shouldReceive('check')
            ->andReturn([ // This simulates what the check method returns
                ['type' => 'error', 'message' => 'Image missing alt attribute', 'element' => 'img'],
                ['type' => 'warning', 'message' => 'Skipped heading level: from h1 to h3', 'element' => 'h3']
            ]);

        // Define the behavior of getRules()
        $ruleRegistryMock->shouldReceive('getRules')
            ->andReturn([$ruleMock]);

        // Mock the AccessibilityScoreCalculator
        /** @var AccessibilityScoreCalculator|Mockery\MockInterface $scoreCalculatorMock */
        $scoreCalculatorMock = Mockery::mock(AccessibilityScoreCalculator::class);

        // Set up the expectation for the calculate() method to return a specific score
        $scoreCalculatorMock->shouldReceive('calculate')
            ->with([
                ['type' => 'error', 'message' => 'Image missing alt attribute', 'element' => 'img'],
                ['type' => 'warning', 'message' => 'Skipped heading level: from h1 to h3', 'element' => 'h3']
            ])
            ->andReturn(80);

        // Create an instance of AccessibilityAnalyzer with the mocked dependencies
        $analyzer = new AccessibilityAnalyzer($ruleRegistryMock, $scoreCalculatorMock);

        // Sample HTML content for analysis
        $html = <<<HTML
    <!DOCTYPE html>
    <html>

    <body>
        <img src="test.jpg">
        <h1>Title</h1>
        <h3>Subtitle</h3>
    </body>

    </html>
    HTML;

        // Call the analyze method (assuming it returns a score)
        $result = $analyzer->analyze($html);

        // Example assertion, modify as per actual logic
        $this->assertEquals(80, $result['score']);
        $this->assertCount(2, $result['issues']);
    }

    protected function tearDown(): void
    {
        // Clean up Mockery
        Mockery::close();
        parent::tearDown();
    }
}
