<x-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-black mb-6">Login</h2>
        
        <form action="/login" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-black mb-1">Email:</label>
                <input type="email" id="email" name="email" class="w-full p-3 border border-black bg-white text-black rounded-lg focus:outline-none focus:border-black" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-black mb-1">Password:</label>
                <input type="password" id="password" name="password" class="w-full p-3 border border-black bg-white text-black rounded-lg focus:outline-none focus:border-black" required>
            </div>
            
            <div>
                <button type="submit" class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition duration-300">Login</button>
            </div>
        </form>
        
        <p class="text-black mt-4">Don't have an account? <a href="/register" class="text-gray-500 hover:text-black">Register</a></p>
    </div>
</x-layout>
