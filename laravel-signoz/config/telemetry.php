return [
    'exporter' => [
        'otel_exporter' => 'otlp',
        'otel_exporter_otlp_endpoint' => env('SIGNOZ_TRACE_URL', 'http://10.8.60.123:4317')
    ]
];

