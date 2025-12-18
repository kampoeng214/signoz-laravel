vi routes/web.php 

<?php

use OpenTelemetry\API\Globals;
use App\Http\Controllers\ApiController;


Route::get('/otel-test', function () {
    $tracer = Globals::tracerProvider()->getTracer('test-laravel');

    $span = $tracer->spanBuilder('laravel-test-span')->startSpan();
    $scope = $span->activate();

    sleep(1);

    $span->end();
    $scope->detach();

    return 'OTEL OK';
});

