<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tarea;

class TareaApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_tarea()
    {
        $response = $this->postJson('/api/tareas', [
            'titulo' => 'Tarea de prueba',
            'descripcion' => 'Descripción de prueba',
            'completada' => false,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['titulo' => 'Tarea de prueba']);
    }

    /** @test */
    public function puede_listar_tareas()
    {
        Tarea::factory()->count(2)->create();

        $response = $this->getJson('/api/tareas');

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    /** @test */
    public function puede_ver_una_tarea()
    {
        $tarea = Tarea::factory()->create();

        $response = $this->getJson('/api/tareas/' . $tarea->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $tarea->id]);
    }

    /** @test */
    public function puede_actualizar_una_tarea()
    {
        $tarea = Tarea::factory()->create();

        $response = $this->putJson('/api/tareas/' . $tarea->id, [
            'titulo' => 'Titulo actualizado',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['titulo' => 'Titulo actualizado']);
    }

    /** @test */
    public function puede_eliminar_una_tarea()
    {
        $tarea = Tarea::factory()->create();

        $response = $this->deleteJson('/api/tareas/' . $tarea->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tareas', ['id' => $tarea->id]);
    }
}
