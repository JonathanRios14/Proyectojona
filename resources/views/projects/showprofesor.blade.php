<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Proyecto Estudiante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('error'))
                        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold">{{ $proyecto->nombre }}</h3>
                    <p class="mt-2">{{ $proyecto->descripcion }}</p>

                    <p class="mt-4 dark:text-indigo-400 font-semibold">Estudiante: {{ $proyecto->estudiante->name }}</p>

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
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
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
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
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
                                <a href="{{ asset('storage/' . $file->ruta) }}" target="_blank" class="text-bg-gray-600 hover:text-indigo-300">
                                    {{ $file->nombre }}
                                </a>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $file->created_at->format('d M Y') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold">Comentarios:</h4>
                        @foreach ($proyecto->comments as $comment)
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-200">Por: {{ $comment->usuario->name }}</p>
                                <p class="text-gray-700 dark:text-white">{{ $comment->contenido }}</p>

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @if ($comment->created_at != $comment->updated_at)
                                        Editado el: {{ $comment->updated_at->format('d M Y H:i') }}
                                    @else
                                        Creado el: {{ $comment->created_at->format('d M Y H:i') }}
                                    @endif
                                </p>

                                @if(auth()->user()->role == 'profesor' && auth()->id() == $comment->usuario_id)
                                    <div class="mt-4">
                                        <a href="{{ route('comments.edit', $comment->id) }}" class="text-indigo-600 hover:text-indigo-800">Editar</a> |
                                        <button type="button" onclick="openDeleteModal({{ $comment->id }})" class="text-red-600 hover:text-red-800">Eliminar</button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>


                    @if(auth()->user()->role == 'profesor')
                        <div class="mt-6">
                            @if ($proyecto->estado !== 'completado')
                                <div class="bg-yellow-400 text-white p-4 rounded-md mb-4">
                                    El proyecto no está en estado "Completado", no puedes agregar una evaluación aún.
                                </div>
                            @else
                                <form action="{{ route('comments.store', $proyecto->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="contenido" class="block text-gray-700 dark:text-gray-200 font-semibold">Nota (Comentario de Evaluación)</label>
                                        <textarea name="contenido" class="w-full p-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" rows="4" placeholder="Escribe una nota para el estudiante (ej. 7/10)" required></textarea>
                                    </div>

                                    <button type="submit" class="mt-4 bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                                        Agregar Nota
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

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


    <div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-xs w-auto">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">¿Estás seguro de que deseas eliminar este comentario?</h3>
            <div class="flex justify-between">
                <button onclick="closeDeleteModal()" class="bg-gray-300 dark:bg-gray-600 text-black dark:text-white py-2 px-4 rounded-md">Cancelar</button>
                <form id="deleteCommentForm" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 dark:bg-red-700 text-white py-2 px-4 rounded-md">Eliminar</button>
                </form>
            </div>
        </div>
    </div>


</x-app-layout>

<script>
    function openDeleteModal(commentId) {
        document.getElementById('deleteModal').classList.remove('hidden');

        const form = document.getElementById('deleteCommentForm');
        form.action = '/comments/' + commentId;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
