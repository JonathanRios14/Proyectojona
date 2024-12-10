<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold">{{ __('Crear un Nuevo Proyecto') }}</h3>

                    <form action="{{ route('projects.store') }}" method="POST" id="create-project-form" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-300">Nombre del Proyecto</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100" required>

                            @error('nombre')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-300">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100" required>{{ old('descripcion') }}</textarea>

                            @error('descripcion')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="mt-4">
                            <label for="estado" class="block text-sm font-medium text-gray-300">Estado</label>
                            <select name="estado" id="estado" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100" required>
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                            </select>

                            @error('estado')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="profesor_id" class="block text-sm font-medium text-gray-300">Profesor</label>
                            <select name="profesor_id" id="profesor_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100" required>
                                <option value="">Seleccionar Profesor</option>
                                @foreach ($profesores as $profesor)
                                    <option value="{{ $profesor->id }}" {{ old('profesor_id') == $profesor->id ? 'selected' : '' }}>{{ $profesor->name }}</option>
                                @endforeach
                            </select>

                            @error('profesor_id')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(auth()->user()->role == 'estudiante')
                            <div class="mt-4">
                                <label for="file" class="block text-sm font-medium text-gray-300">Subir Archivo</label>
                                <input type="file" name="file" id="file" class="mt-1 block w-full p-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-100">
                                @error('file')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="mt-4">
                            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-indigo-700">
                                Crear Proyecto
                            </button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-gray-600">
                            Volver a la lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
