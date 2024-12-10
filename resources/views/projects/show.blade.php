<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold">{{ $proyecto->nombre }}</h3>
                    <p class="mt-2">{{ $proyecto->descripcion }}</p>

                    <p class="mt-4 dark:text-indigo-400 font-semibold">Profesor: {{ $proyecto->profesor->name }}</p>

                    <div class="mt-4">
                        <p class="font-semibold">Estado:
                            <span class="text-gray-800 dark:text-gray-300">{{ ucfirst($proyecto->estado) }}</span>
                        </p>

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

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold">Archivos Subidos</h4>
                        @foreach ($proyecto->files as $file)
                            <div class="flex items-center justify-between bg-gray-200 dark:bg-gray-600 p-2 rounded-md mb-2">
                                <!-- Enlace para mostrar el archivo en una nueva pestaña -->
                                <a href="{{ asset('storage/' . $file->ruta) }}" target="_blank" class="text-bg-gray-600 hover:text-indigo-300">
                                    {{ $file->nombre }}
                                </a>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $file->created_at->format('d M Y') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold">Comentario de Evaluación:</h4>
                        @if ($proyecto->comments->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Aún no hay un comentario de evaluación para este proyecto.</p>
                        @else
                            @foreach ($proyecto->comments as $comment)
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mt-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-200">Profesor: {{ $comment->usuario->name }}</p>
                                    <p class="text-gray-700 dark:text-white">{{ $comment->contenido }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->format('d M Y H:i') }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>


                    <div class="mt-6">
                        <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md transition-all duration-300 transform hover:scale-105 hover:bg-gray-600">
                            Volver a la lista de proyectos
                        </a>
                    </div>

                    <div class="relative">
                        <div class="absolute bottom-4 right-0 text-sm text-purple-600">
                            Fecha de Creación: {{ $proyecto->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
