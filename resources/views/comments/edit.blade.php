<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Comentario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="contenido" class="block text-gray-700 dark:text-gray-200 font-semibold">Comentario</label>
                            <textarea name="contenido" class="w-full p-4 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" rows="4" required>{{ $comment->contenido }}</textarea>
                        </div>

                        <button type="submit" class="mt-4 bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                            Actualizar Comentario
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
