<x-layouts::app :title="'Companion — ' . config('app.name')">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
            {{ session('status') }}
        </div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
</x-layouts::app>
