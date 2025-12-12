<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tareas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind desde CDN (solo para que se vea decente) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">

    <div class="max-w-3xl mx-auto py-10">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 text-center">
            API de Tareas - Panel Básico
        </h1>

        <!-- Formulario para crear tarea -->
        <div class="bg-white rounded-xl shadow-md p-5 mb-6">
            <h2 class="text-xl font-semibold mb-4">Crear nueva tarea</h2>
            <form id="form-tarea" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Título</label>
                    <input
                        type="text"
                        id="titulo"
                        class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Descripción</label>
                    <textarea
                        id="descripcion"
                        class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="3"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg"
                >
                    Guardar tarea
                </button>
                <span id="mensaje" class="text-sm ml-3"></span>
            </form>
        </div>

        <!-- Listado de tareas -->
        <div class="bg-white rounded-xl shadow-md p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Listado de tareas</h2>
                <button
                    id="btn-recargar"
                    class="text-sm px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200"
                >
                    Recargar
                </button>
            </div>
            <div id="contenedor-tareas" class="space-y-3">
                <!-- aquí se insertan las tarjetas de tareas -->
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api/tareas'; // misma app, solo prefijo /api

        const contenedorTareas = document.getElementById('contenedor-tareas');
        const formTarea = document.getElementById('form-tarea');
        const mensaje = document.getElementById('mensaje');
        const btnRecargar = document.getElementById('btn-recargar');

        async function cargarTareas() {
            contenedorTareas.innerHTML = '<p class="text-slate-500">Cargando tareas...</p>';
            try {
                const resp = await fetch(API_URL);
                if (!resp.ok) throw new Error('Error al cargar tareas');

                const data = await resp.json();
                if (!Array.isArray(data) || data.length === 0) {
                    contenedorTareas.innerHTML = '<p class="text-slate-500">No hay tareas registradas.</p>';
                    return;
                }

                contenedorTareas.innerHTML = '';
                data.forEach(tarea => {
                    const card = document.createElement('div');
                    card.className = 'border border-slate-200 rounded-lg px-4 py-3 flex items-start justify-between';

                    card.innerHTML = `
                        <div>
                            <h3 class="font-semibold ${tarea.completada ? 'line-through text-slate-400' : ''}">
                                ${tarea.titulo}
                            </h3>
                            <p class="text-sm text-slate-600 mt-1">
                                ${tarea.descripcion ?? ''}
                            </p>
                            <p class="text-xs mt-1">
                                Estado:
                                <span class="${tarea.completada ? 'text-green-600' : 'text-orange-600'} font-semibold">
                                    ${tarea.completada ? 'Completada' : 'Pendiente'}
                                </span>
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 ml-4">
                            <button
                                class="px-3 py-1 text-xs rounded-lg ${tarea.completada ? 'bg-slate-200 text-slate-600' : 'bg-green-600 text-white'}"
                                onclick="toggleCompletada(${tarea.id}, ${tarea.completada ? 'false' : 'true'})"
                            >
                                ${tarea.completada ? 'Marcar pendiente' : 'Marcar completa'}
                            </button>
                            <button
                                class="px-3 py-1 text-xs rounded-lg bg-red-600 text-white"
                                onclick="eliminarTarea(${tarea.id})"
                            >
                                Eliminar
                            </button>
                        </div>
                    `;

                    contenedorTareas.appendChild(card);
                });
            } catch (err) {
                contenedorTareas.innerHTML = '<p class="text-red-600 text-sm">Error cargando tareas.</p>';
                console.error(err);
            }
        }

        async function crearTarea(titulo, descripcion) {
            try {
                const resp = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        titulo,
                        descripcion,
                        completada: false,
                    }),
                });

                if (!resp.ok) {
                    const error = await resp.text();
                    console.error('Error respuesta POST:', error);
                    throw new Error('Error al crear tarea');
                }

                mensaje.textContent = 'Tarea creada correctamente ✅';
                mensaje.className = 'text-sm text-green-600 ml-3';
                formTarea.reset();
                await cargarTareas();
            } catch (err) {
                console.error(err);
                mensaje.textContent = 'Error al crear tarea ❌';
                mensaje.className = 'text-sm text-red-600 ml-3';
            }
        }

        async function toggleCompletada(id, nuevoEstado) {
            try {
                const resp = await fetch(`${API_URL}/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        completada: nuevoEstado,
                    }),
                });

                if (!resp.ok) throw new Error('Error al actualizar tarea');
                await cargarTareas();
            } catch (err) {
                console.error(err);
                alert('No se pudo actualizar el estado de la tarea');
            }
        }

        async function eliminarTarea(id) {
            if (!confirm('¿Seguro que quieres eliminar esta tarea?')) return;

            try {
                const resp = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (!resp.ok) throw new Error('Error al eliminar tarea');
                await cargarTareas();
            } catch (err) {
                console.error(err);
                alert('No se pudo eliminar la tarea');
            }
        }

        // Eventos
        formTarea.addEventListener('submit', (e) => {
            e.preventDefault();
            mensaje.textContent = '';
            const titulo = document.getElementById('titulo').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            if (!titulo) {
                mensaje.textContent = 'El título es obligatorio';
                mensaje.className = 'text-sm text-red-600 ml-3';
                return;
            }
            crearTarea(titulo, descripcion);
        });

        btnRecargar.addEventListener('click', () => {
            cargarTareas();
        });

        // Cargar al inicio
        cargarTareas();

        // Exponer funciones al scope global para onclick del HTML
        window.toggleCompletada = toggleCompletada;
        window.eliminarTarea = eliminarTarea;
    </script>
</body>
</html>
