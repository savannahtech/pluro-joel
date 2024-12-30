<?php

namespace App\Providers;

use App\Services\AccessibilityAnalyzer;
use App\Services\Rules\RuleRegistry;
use App\Services\Scoring\AccessibilityScoreCalculator;
use Illuminate\Support\ServiceProvider;

class AccessibilityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RuleRegistry::class);
        
        $this->app->singleton(AccessibilityScoreCalculator::class, function ($app) {
            return new AccessibilityScoreCalculator(
                config('accessibility.scoring.base_score'),
                config('accessibility.scoring.penalties')
            );
        });

        $this->app->singleton(AccessibilityAnalyzer::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/accessibility.php' => config_path('accessibility.php'),
        ], 'accessibility-config');
    }
}