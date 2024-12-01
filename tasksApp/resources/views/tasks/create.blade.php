<x-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-black mb-6">Create Task</h2>
        
        <form action="/tasks" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-black mb-1">Title:</label>
                <input type="text" id="title" name="title" class="w-full p-3 border border-black bg-white text-black rounded-lg focus:outline-none focus:border-black" required>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-black mb-1">Description:</label>
                <textarea id="description" name="description" class="w-full p-3 border border-black bg-white text-black rounded-lg focus:outline-none focus:border-black"></textarea>
            </div>
            
            <div>
                <button type="submit" class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition duration-300">Create Task</button>
            </div>
        </form>
        
        <a href="/tasks" class="block text-gray-500 hover:text-black mt-4">Back to Task List</a>
    </div>
</x-layout>
