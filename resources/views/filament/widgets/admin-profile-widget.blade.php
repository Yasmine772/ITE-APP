<div class="p-4 text-center">
    <img
        src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
        alt="Admin Avatar"
        class="w-20 h-20 rounded-full mx-auto shadow-lg"
    >
    <h2 class="mt-2 font-bold text-lg">{{ Auth::user()->name }}</h2>
    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
</div>
