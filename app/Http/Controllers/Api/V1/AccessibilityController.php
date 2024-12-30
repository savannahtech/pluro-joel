<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\AccessibilityAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Rules\RuleRegistry;
use App\Services\Scoring\AccessibilityScoreCalculator;

class AccessibilityController extends Controller
{
    public function analyze(Request $request): JsonResponse
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:html,htm',
        ]);

        // Read the content of the uploaded file
        $content = file_get_contents($request->file('file')->path());

        // Instantiate required dependencies
        $ruleRegistry = app(RuleRegistry::class);
        $scoreCalculator = app(AccessibilityScoreCalculator::class);

        // Create an instance of AccessibilityAnalyzer
        $analyzer = new AccessibilityAnalyzer($ruleRegistry, $scoreCalculator);

        // Analyze the file content
        $analysisResult = $analyzer->analyze($content);

        // Return the response in JSON format
        return response()->json($analysisResult);
    }
}
