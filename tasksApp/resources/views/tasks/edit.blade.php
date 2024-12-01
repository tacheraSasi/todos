<x-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-black mb-6">Edit Task</h2>
        
        <form action="/tasks/{{ $task->id }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="title" class="block text-black mb-1">Title:</label>
                <input type="text" id="title" name="title" value="{{ $task->title }}" class="w-full p-3 border border-black bg-white text-black rounded-lg focus:outline-none focus:border-black" required>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-black mb-1">Description:</label>
                <textarea id="description" name="description" class="w-full p-3 border border-black bg-white text-black rounded-lg focus:outline-none focus:border-black">{{ $task->description }}</textarea>
            </div>
            
            <div>
                <button type="submit" class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition duration-300">Update Task</button>
            </div>
        </form>
        
        <a href="/tasks" class="block text-gray-500 hover:text-black mt-4">Back to Task List</a>
    </div>
</x-layout>
