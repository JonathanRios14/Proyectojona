<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Proyecto') }}: {{ $proyecto->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold">{{ __('Editar Proyecto') }}</h3>

                    <!-- Mostrar fecha de edición -->
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Última edición: {{ $proyecto->updated_at ? $proyecto->updated_at->format('d M Y H:i') : 'Nunca editado' }}
                    </p>

                    <!-- Formulario para editar el proyecto -->
                    <form action="{{ route('projects.update', $proyecto->id) }}" method="POST" id="edit-project-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nombre del Proyecto -->
                        <div class="mt-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-300">Nombre del Proyecto</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $proyecto->nombre) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100" required>

                            @error('nombre')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-300">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100" required>{{ old('descripcion', $proyecto->descripcion) }}</textarea>

                            @error('descripcion')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="mt-4">
                            <label for="estado" class="block text-sm font-medium text-gray-300">Estado</label>
                            <select name="estado" id="estado" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100" required>
                                <option value="activo" {{ old('estado', $proyecto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="completado" {{ old('estado', $proyecto->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                            </select>

                            @error('estado')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Archivo Actual (si existe) -->
                        @if($proyecto->files()->exists())
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-300">Archivo Actual:</p>
                                <a href="{{ asset('storage/' . $proyecto->files->first()->ruta) }}" class="text-blue-500" target="_blank">Ver archivo actual</a>
                                <label for="delete_file" class="block text-sm font-medium text-gray-300 mt-2">¿Eliminar este archivo?</label>
                                <input type="checkbox" name="delete_file" id="delete_file" class="mt-1">
                            </div>
                        @endif

                        <!-- Subir un nuevo archivo (opcional) -->
                        <div class="mt-4">
                            <label for="file" class="block text-sm font-medium text-gray-300">Subir un nuevo archivo (opcional)</label>
                            <input type="file" name="file" id="file" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100">

                            @error('file')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón para actualizar -->
                        <div class="mt-4">
                            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-indigo-700">
                                Actualizar Proyecto
                            </button>
                        </div>
                    </form>

                    <!-- Botón para volver a la lista -->
                    <div class="mt-6">
                        <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-gray-600">
                            Volver a la lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validación del formulario antes de enviarlo
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('edit-project-form');

            form.addEventListener('submit', function (event) {
                let isValid = true;
                const deleteFile = document.getElementById('delete_file').checked;
                const newFile = document.getElementById('file').files.length > 0;

                // Si se marca la opción de eliminar el archivo, y no se sube uno nuevo, el formulario no se enviará
                if (deleteFile && !newFile) {
                    alert('Si eliminas el archivo, debes subir uno nuevo.');
                    isValid = false;
                }

                // Validación del nombre
                const nombre = document.getElementById('nombre');
                if (nombre.value.trim() === '') {
                    isValid = false;
                    alert('El nombre del proyecto es obligatorio.');
                }

                // Validación de la descripción
                const descripcion = document.getElementById('descripcion');
                if (descripcion.value.trim() === '') {
                    isValid = false;
                    alert('La descripción del proyecto es obligatoria.');
                }

                // Validación del estado
                const estado = document.getElementById('estado');
                if (estado.value === '') {
                    isValid = false;
                    alert('Por favor selecciona el estado del proyecto.');
                }

                // Si alguna validación falla, evitar el envío del formulario
                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>
