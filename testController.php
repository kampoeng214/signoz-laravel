<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\SpanKind;

class ApiController extends Controller
{
    public function otelTest()
    {
        $tracer = Globals::tracerProvider()->getTracer('laravel-otel-test');

        $span = $tracer->spanBuilder('otel-test-span')
            ->setSpanKind(SpanKind::KIND_SERVER)
            ->startSpan();

        // simulasi kerja
        usleep(300000);

        $span->setAttribute('test.from', 'TestController');
        $span->setAttribute('test.ok', true);

        $span->end();

        return response()->json([
            'status' => 'OK',
            'message' => 'OTEL TEST ONLY',
        ]);
    }
}
