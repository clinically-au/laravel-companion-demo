<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('API Explorer')] class extends Component
{
    public string $activeSection = 'environment';

    #[Computed]
    public function environment(): array
    {
        return [
            'app_name' => config('app.name'),
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'drivers' => [
                'database' => config('database.default'),
                'cache' => config('cache.default'),
                'queue' => config('queue.default'),
                'mail' => config('mail.default'),
                'session' => config('session.driver'),
            ],
        ];
    }

    #[Computed]
    public function routes(): array
    {
        return collect(app('router')->getRoutes()->getRoutes())
            ->map(fn ($route) => [
                'methods' => $route->methods(),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware(),
            ])
            ->sortBy('uri')
            ->values()
            ->all();
    }

    #[Computed]
    public function commands(): array
    {
        $whitelist = (array) config('companion.whitelisted_commands', []);

        return collect(Artisan::all())
            ->filter(fn ($cmd) => in_array($cmd->getName(), $whitelist, true))
            ->map(fn ($cmd) => [
                'name' => $cmd->getName(),
                'description' => $cmd->getDescription(),
            ])
            ->sortBy('name')
            ->values()
            ->all();
    }

    #[Computed]
    public function queues(): array
    {
        $connection = config('queue.default');

        return [
            'connection' => $connection,
            'driver' => config("queue.connections.{$connection}.driver"),
            'default_size' => Queue::size('default'),
            'failed_count' => DB::table('failed_jobs')->count(),
        ];
    }

    #[Computed]
    public function cacheInfo(): array
    {
        $driver = config('cache.default');

        return [
            'driver' => $driver,
            'store' => config("cache.stores.{$driver}.driver"),
        ];
    }

    #[Computed]
    public function schedule(): array
    {
        $schedule = app(Schedule::class);

        return collect($schedule->events())
            ->map(fn ($event) => [
                'command' => $event->command ? Str::after($event->command, "'artisan' ") : ($event->description ?? 'Closure'),
                'expression' => $event->expression,
                'description' => $event->description,
                'without_overlapping' => $event->withoutOverlapping,
                'on_one_server' => $event->onOneServer,
            ])
            ->values()
            ->all();
    }

    #[Computed]
    public function migrations(): array
    {
        return DB::table('migrations')
            ->orderBy('batch')
            ->orderBy('id')
            ->get()
            ->groupBy('batch')
            ->map(fn ($migrations, $batch) => [
                'batch' => $batch,
                'migrations' => $migrations->pluck('migration')->values()->all(),
            ])
            ->values()
            ->all();
    }

    #[Computed]
    public function events(): array
    {
        return collect(Event::getRawListeners())
            ->filter(fn ($listeners, $event) => str_starts_with($event, 'App\\'))
            ->map(fn ($listeners, $event) => [
                'event' => $event,
                'listeners' => collect($listeners)->map(fn ($listener) => is_string($listener) ? $listener : 'Closure')->all(),
            ])
            ->values()
            ->all();
    }

    #[Computed]
    public function logs(): array
    {
        $path = storage_path('logs');
        if (! is_dir($path)) {
            return [];
        }

        return collect(scandir($path) ?: [])
            ->filter(fn (string $file) => str_ends_with($file, '.log'))
            ->map(fn (string $file) => [
                'name' => $file,
                'size' => number_format((filesize("{$path}/{$file}") ?: 0) / 1024, 1) . ' KB',
                'last_modified' => date('M j, H:i', filemtime("{$path}/{$file}") ?: 0),
            ])
            ->sortByDesc('last_modified')
            ->values()
            ->all();
    }

    #[Computed]
    public function configSample(): array
    {
        return [
            'app.name' => config('app.name'),
            'app.env' => config('app.env'),
            'app.debug' => config('app.debug'),
            'app.url' => config('app.url'),
            'app.timezone' => config('app.timezone'),
            'app.key' => '********',
            'database.default' => config('database.default'),
            'cache.default' => config('cache.default'),
            'queue.default' => config('queue.default'),
            'mail.default' => config('mail.default'),
            'session.driver' => config('session.driver'),
            'companion.path' => config('companion.path'),
            'companion.audit.enabled' => config('companion.audit.enabled'),
            'companion.rate_limit.api' => config('companion.rate_limit.api'),
        ];
    }
}; ?>

<section class="w-full">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">API Explorer</flux:heading>
            <flux:text class="mt-1">Browse companion API features directly. These mirror what the JSON API returns to authenticated agents.</flux:text>
        </div>

        {{-- Section Nav --}}
        <div class="flex flex-wrap gap-1.5">
            @foreach ([
                'environment' => 'Environment',
                'routes' => 'Routes',
                'commands' => 'Commands',
                'queues' => 'Queues',
                'cache' => 'Cache',
                'config' => 'Config',
                'logs' => 'Logs',
                'schedule' => 'Schedule',
                'migrations' => 'Migrations',
                'events' => 'Events',
            ] as $key => $label)
                <flux:button
                    wire:click="$set('activeSection', '{{ $key }}')"
                    size="sm"
                    :variant="$activeSection === $key ? 'filled' : 'ghost'"
                >
                    {{ $label }}
                </flux:button>
            @endforeach
        </div>

        {{-- Environment --}}
        @if ($activeSection === 'environment')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Environment</flux:heading>
                    <flux:text size="sm">GET /companion/api/environment</flux:text>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->environment as $key => $value)
                        <div class="flex items-start justify-between px-5 py-3">
                            <span class="font-mono text-sm text-zinc-500">{{ $key }}</span>
                            @if (is_array($value))
                                <div class="text-right">
                                    @foreach ($value as $k => $v)
                                        <div class="text-sm"><span class="text-zinc-400">{{ $k }}:</span> {{ $v }}</div>
                                    @endforeach
                                </div>
                            @elseif (is_bool($value))
                                <flux:badge size="sm" :color="$value ? 'green' : 'zinc'">{{ $value ? 'true' : 'false' }}</flux:badge>
                            @else
                                <span class="text-sm font-medium">{{ $value }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Routes --}}
        @if ($activeSection === 'routes')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Routes ({{ count($this->routes) }})</flux:heading>
                    <flux:text size="sm">GET /companion/api/routes</flux:text>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-2.5 font-medium">Method</th>
                                <th class="px-4 py-2.5 font-medium">URI</th>
                                <th class="px-4 py-2.5 font-medium">Name</th>
                                <th class="px-4 py-2.5 font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($this->routes as $route)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        @foreach ($route['methods'] as $method)
                                            @if ($method !== 'HEAD')
                                                <flux:badge size="sm" :color="match($method) { 'GET' => 'green', 'POST' => 'blue', 'PUT', 'PATCH' => 'amber', 'DELETE' => 'red', default => 'zinc' }">{{ $method }}</flux:badge>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 font-mono text-xs">{{ $route['uri'] }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-xs text-zinc-500">{{ $route['name'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-zinc-500">{{ Str::afterLast($route['action'], '\\') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Commands --}}
        @if ($activeSection === 'commands')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Whitelisted Commands ({{ count($this->commands) }})</flux:heading>
                    <flux:text size="sm">GET /companion/api/commands/whitelisted</flux:text>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->commands as $command)
                        <div class="flex items-center justify-between px-5 py-3">
                            <code class="text-sm font-medium">{{ $command['name'] }}</code>
                            <span class="text-sm text-zinc-500">{{ $command['description'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Queues --}}
        @if ($activeSection === 'queues')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Queues</flux:heading>
                    <flux:text size="sm">GET /companion/api/queues</flux:text>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->queues as $key => $value)
                        <div class="flex items-center justify-between px-5 py-3">
                            <span class="font-mono text-sm text-zinc-500">{{ $key }}</span>
                            <span class="text-sm font-medium">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Cache --}}
        @if ($activeSection === 'cache')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Cache Info</flux:heading>
                    <flux:text size="sm">GET /companion/api/cache/info</flux:text>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->cacheInfo as $key => $value)
                        <div class="flex items-center justify-between px-5 py-3">
                            <span class="font-mono text-sm text-zinc-500">{{ $key }}</span>
                            <span class="text-sm font-medium">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Config --}}
        @if ($activeSection === 'config')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Config (sample)</flux:heading>
                    <flux:text size="sm">GET /companion/api/config — sensitive values redacted</flux:text>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->configSample as $key => $value)
                        <div class="flex items-center justify-between px-5 py-3">
                            <span class="font-mono text-sm text-zinc-500">{{ $key }}</span>
                            @if ($value === '********')
                                <flux:badge size="sm" color="red">REDACTED</flux:badge>
                            @elseif (is_bool($value))
                                <flux:badge size="sm" :color="$value ? 'green' : 'zinc'">{{ $value ? 'true' : 'false' }}</flux:badge>
                            @else
                                <span class="text-sm font-medium">{{ $value }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Logs --}}
        @if ($activeSection === 'logs')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Log Files</flux:heading>
                    <flux:text size="sm">GET /companion/api/logs</flux:text>
                </div>
                @if (empty($this->logs))
                    <div class="px-5 py-8 text-center text-sm text-zinc-400">No log files found.</div>
                @else
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($this->logs as $log)
                            <div class="flex items-center justify-between px-5 py-3">
                                <code class="text-sm">{{ $log['name'] }}</code>
                                <div class="flex items-center gap-4 text-sm text-zinc-500">
                                    <span>{{ $log['size'] }}</span>
                                    <span>{{ $log['last_modified'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Schedule --}}
        @if ($activeSection === 'schedule')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Scheduled Commands ({{ count($this->schedule) }})</flux:heading>
                    <flux:text size="sm">GET /companion/api/schedule</flux:text>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-2.5 font-medium">Command</th>
                                <th class="px-4 py-2.5 font-medium">Expression</th>
                                <th class="px-4 py-2.5 font-medium">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($this->schedule as $event)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-mono text-xs">{{ $event['command'] }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 font-mono text-xs text-zinc-500">{{ $event['expression'] }}</td>
                                    <td class="px-4 py-2 text-sm text-zinc-500">{{ $event['description'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Migrations --}}
        @if ($activeSection === 'migrations')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Migrations</flux:heading>
                    <flux:text size="sm">GET /companion/api/migrations</flux:text>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->migrations as $batch)
                        <div class="px-5 py-3">
                            <div class="mb-2 text-xs font-medium text-zinc-400">Batch {{ $batch['batch'] }}</div>
                            @foreach ($batch['migrations'] as $migration)
                                <div class="font-mono text-sm">{{ $migration }}</div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Events --}}
        @if ($activeSection === 'events')
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <flux:heading size="lg">Events & Listeners</flux:heading>
                    <flux:text size="sm">GET /companion/api/events</flux:text>
                </div>
                @if (empty($this->events))
                    <div class="px-5 py-8 text-center text-sm text-zinc-400">No app events registered.</div>
                @else
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($this->events as $event)
                            <div class="px-5 py-3">
                                <code class="text-sm font-medium">{{ class_basename($event['event']) }}</code>
                                <div class="mt-1.5 space-y-1">
                                    @foreach ($event['listeners'] as $listener)
                                        <div class="flex items-center gap-2 text-sm text-zinc-500">
                                            <span class="text-zinc-300 dark:text-zinc-600">&rarr;</span>
                                            {{ Str::afterLast($listener, '\\') }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
