<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-xl mb-4">{{ __('Mis Proyectos') }}</h3>

                    @if(auth()->user()->role == 'estudiante')
                        <a href="{{ route('projects.create') }}" class="bg-indigo-laravel text-white py-2 px-4 rounded-md mb-6 inline-block transition-all duration-300 transform hover:scale-105 hover:bg-indigo-600">
                            Crear Proyecto
                        </a>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($proyectos as $proyecto)
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                <h4 class="font-semibold text-xl text-gray-900 dark:text-gray-100">{{ $proyecto->nombre }}</h4>
                                <p class="text-gray-700 dark:text-gray-300 mt-2">{{ Str::limit($proyecto->descripcion, 100) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Estado: {{ $proyecto->estado }}</p>

                                <div class="mt-4">
                                    @if(auth()->user()->role == 'estudiante')
                                        <p class="text-sm text-gray-500 dark:text-indigo-300">Profesor: {{ $proyecto->profesor->name }}</p>
                                    @elseif(auth()->user()->role == 'profesor')
                                        <p class="text-sm text-gray-500 dark:text-indigo-300">Estudiante: {{ $proyecto->estudiante->name }}</p>

                                        <a href="{{ route('projects.showprofesor', $proyecto->id) }}" class="bg-gray-800 text-white py-2 px-4 rounded-md mt-2 inline-block transition-all duration-300 transform hover:scale-105 hover:bg-indigo-600">
                                            <img src="{{ asset('ojo.png') }}" alt="Ver Proyecto Estudiante" class="w-5 h-5 inline-block mr-2" />
                                            Ver Proyecto
                                        </a>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    @foreach ($proyecto->files as $file)
                                        <div class="flex items-center justify-between bg-gray-200 dark:bg-gray-600 p-2 rounded-md mb-2">
                                            <a href="{{ asset('storage/' . $file->ruta) }}" target="{{ $file->nombre }}" class="text-bg-gray-600 hover:text-indigo-300">
                                                {{ $file->nombre }}
                                            </a>
                                            <a href="{{ asset('storage/' . $file->ruta) }}" download="{{ $file->nombre }}" class="text-bg-gray-600 hover:text-indigo-300">
                                                Descargar
                                            </a>
                                        </div>
                                    @endforeach

                                </div>

                                    <div class="mt-4">
                                        @if ($proyecto->estado == 'activo')
                                            <div class="relative pt-1">
                                                <div class="flex mb-2 items-center justify-between">
                                                    <span class="text-sm font-semibold inline-block py-1 uppercase">Progreso: 50%</span>
                                                </div>
                                                <div class="flex mb-2 items-center justify-between">
                                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                        <div class="bg-indigo-400 h-2.5 rounded-full" style="width: 50%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($proyecto->estado == 'completado')
                                            <div class="relative pt-1">
                                                <div class="flex mb-2 items-center justify-between">
                                                    <span class="text-sm font-semibold inline-block py-1 uppercase">Progreso: 100%</span>
                                                </div>
                                                <div class="flex mb-2 items-center justify-between">
                                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                                <div class="bg-green-600 h-2.5 rounded-full" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                <div class="mt-4">
                                    @if(auth()->user()->role == 'estudiante' && $proyecto->estudiante_id == auth()->id())
                                        <div class="flex space-x-2">
                                            <a href="{{ route('projects.show', $proyecto->id) }}" class="bg-gray-800 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-indigo-600">
                                                <img src="{{ asset('ojo.png') }}" alt="Ver" class="w-5 h-5 inline-block" />
                                            </a>

                                            <a href="{{ route('projects.edit', $proyecto->id) }}" class="bg-gray-800 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-indigo-600">
                                                Editar
                                            </a>

                                            <form action="{{ route('projects.destroy', $proyecto->id) }}" method="POST" id="deleteForm-{{ $proyecto->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="bg-gray-800 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-red-600" onclick="openDeleteModal({{ $proyecto->id }})">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 text-right text-sm text-purple-600">
                                    {{ $proyecto->created_at->format('d M Y') }}
                                </div>
                            </div>

                            <div id="deleteModal-{{ $proyecto->id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-800 bg-opacity-50">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">¿Estás seguro de que quieres eliminar este proyecto?</h3>
                                    <div class="mt-4 flex justify-between">
                                        <button onclick="closeDeleteModal({{ $proyecto->id }})" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400">
                                            Cancelar
                                        </button>
                                        <button onclick="document.getElementById('deleteForm-{{ $proyecto->id }}').submit();" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                                            Confirmar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $proyectos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(proyectoId) {
            document.getElementById('deleteModal-' + proyectoId).classList.remove('hidden');
        }

        function closeDeleteModal(proyectoId) {
            document.getElementById('deleteModal-' + proyectoId).classList.add('hidden');
        }
    </script>
</x-app-layout>
