<x-layout>
    <x-card>
        <h1 class="font-bold text-lg">Welcome to Tasks App</h1>
        <ul class="flex gap-4 ">
            <li class="flex-1 w-full">
                <a
                class="text-center cursor-pointer text-lg w-full rounded-md bg-gray-200 p-2" 
                href="{{route("login")}}">login</a>
            </li>
            <li class="flex-1 w-full">
                <a
                class="text-center cursor-pointer text-lg w-full rounded-md bg-gray-200 p-2" 
                href="{{route("register")}}">register</a>
            </li>
        </ul>
    </x-card>
</x-layout>