use OpenTelemetry\Contrib\Exporter\OTLP\OTLPExporter;

public function register()
{
    $exporter = new OTLPExporter(
        endpoint: env('SIGNOZ_TRACE_URL', 'http://10.8.60.123:4318')
    );

    $tracerProvider = new TracerProvider();
    $tracerProvider->addSpanProcessor(new SimpleSpanProcessor($exporter));
    // register tracer provider ke globals jika perlu
}

