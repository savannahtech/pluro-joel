<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AccessibilityAnalyzerTest extends TestCase
{
    /**
     * Test the ability to analyze a valid HTML file.
     *
     * @return void
     */
    public function test_can_analyze_html_file()
    {
        // Fake HTML content with an image missing alt attribute and heading levels skipped.
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

        // Create a fake HTML file with the above content.
        $file = UploadedFile::fake()->createWithContent('test.html', $html);

        // Make a POST request to the '/api/v1/analyze' route with the HTML file.
        $response = $this->postJson('/api/v1/analyze', [
            'file' => $file
        ]);

        // Assert the response has a 200 OK status.
        $response->assertStatus(200)
            // Assert the response JSON structure contains the 'score' and 'issues'.
            ->assertJsonStructure([
                'score',
                'issues' => [
                    '*' => ['type', 'message', 'element']
                ]
            ])
            // Assert the response includes expected issues for the uploaded HTML.
            ->assertJson([
                'issues' => [
                    [
                        'type' => 'error',
                        'message' => 'Image missing alt attribute',
                        'element' => '/html/body/img',
                        'wcagRef' => [
                            'id' => '1.1.1',
                            'url' => 'https://www.w3.org/WAI/WCAG21/Understanding/non-text-content',
                            'level' => 'A',
                        ],
                    ],
                    [
                        'type' => 'warning',
                        'message' => 'Skipped heading level: from h1 to h3',
                        'element' => '/html/body/h3',
                    ],
                ],
            ]);
    }

    /**
     * Test the rejection of invalid file type for analysis.
     *
     * @return void
     */
    public function test_rejects_invalid_file_type()
    {
        // Create a fake non-HTML file (e.g., a text file).
        $file = UploadedFile::fake()->create('test.txt', 100);

        // Make a POST request to the '/api/v1/analyze' route with the non-HTML file.
        $response = $this->postJson('/api/v1/analyze', [
            'file' => $file
        ]);

        // Assert the response status is 422 Unprocessable Entity for invalid file type.
        $response->assertStatus(422);
    }
}
