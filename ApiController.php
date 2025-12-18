cat app/Http/Controllers/ApiController.php 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// Tambahkan ini:
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\SpanKind;

class ApiController extends Controller
{
    public function getRandomUser()
    {
        // --- START CUSTOM SPAN ---
        $tracer = Globals::tracerProvider()->getTracer('laravel-custom');

        $span = $tracer->spanBuilder('getRandomUser-controller')
            ->setSpanKind(SpanKind::KIND_SERVER)
            ->startSpan();
        // --- END CUSTOM SPAN ---

        // Call Random User API
        $response = Http::get('https://randomuser.me/api/');

        // Tambah attribute span agar terlihat di Signoz
        $span->setAttribute('api.status', $response->status());
        $span->setAttribute('api.url', 'https://randomuser.me/api/');
        $span->setAttribute('api.success', $response->successful());

        // Convert to array
        $data = $response->json();

        // Tambah attribute data ke span
        if (isset($data['results'][0])) {
            $span->setAttribute('user.first_name', $data['results'][0]['name']['first'] ?? 'unknown');
            $span->setAttribute('user.email', $data['results'][0]['email'] ?? 'unknown');
        }

        // END span
        $span->end();

        // Send to view
        return view('random-user', ['user' => $data['results'][0]]);
    }
}
