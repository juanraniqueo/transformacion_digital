<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Verifica que la API de tareas responde correctamente.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/api/tareas');

        $response->assertStatus(200);
    }
}
