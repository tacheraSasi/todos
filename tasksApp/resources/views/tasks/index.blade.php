<x-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
        <h1 class="text-3xl font-bold text-black mb-6">Hello, {{ Auth::user()->name }}</h1>
        
        <a href="/tasks/create" class="inline-block mb-4 bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition duration-300">Create New Task</a>
        
        <ul class="list-disc list-inside space-y-4">
            @if (isset($tasks) && $tasks->isNotEmpty())
                @foreach ($tasks as $task)
                    <li class="flex justify-between items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                        <a href="/tasks/{{ $task->id }}/edit" class="text-black font-semibold hover:text-gray-700">{{ $task->title }}</a>
                        
                        <form action="/tasks/{{ $task->id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-500 transition duration-300">
                                Delete
                            </button>
                        </form>
                    </li>
                @endforeach
            @else
                <h3 class="text-black mt-6">No tasks found. <a href="{{ route('create-task') }}" class="text-gray-500 hover:text-black">Create one</a></h3>
            @endif
        </ul>
    </div>
</x-layout>
