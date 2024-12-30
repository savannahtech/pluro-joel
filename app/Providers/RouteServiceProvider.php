<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('api/v1')
    ->group(base_path('routes/api.php'));

Route::middleware('api')
    ->prefix('api/v2')
    ->group(base_path('routes/api_v2.php'));

Route::middleware('web')
    ->group(base_path('routes/web.php'));
