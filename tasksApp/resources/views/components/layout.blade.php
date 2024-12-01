<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tasks App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-black">
    <header class="bg-white text-black p-4 shadow-sm">
        <h1 class="text-4xl font-bold text-center">TASKS APP</h1>
    </header>
    
    <main class="p-8">
        {{$slot}}
    </main>
    
    <footer class="bg-white w-full fixed bottom-0 text-black text-center p-1 mt-12">
        <p>&copy; 2024 Tasks App. All rights reserved.</p>
    </footer>
</body>
</html>
