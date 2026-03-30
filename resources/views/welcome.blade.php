<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel Companion Demo</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-50 font-[Instrument_Sans] text-zinc-900 antialiased dark:bg-zinc-900 dark:text-zinc-100">
        {{-- Header --}}
        <header class="border-b border-zinc-200 dark:border-zinc-800">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-8 items-center justify-center rounded-md bg-zinc-900 dark:bg-zinc-100">
                        <x-app-logo-icon class="size-5 fill-current text-white dark:text-zinc-900" />
                    </div>
                    <span class="text-lg font-semibold">Companion Demo</span>
                </div>
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-600 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md bg-zinc-900 px-3.5 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200">Log in</a>
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Hero --}}
        <main class="mx-auto max-w-5xl px-6 py-16">
            <div class="max-w-2xl">
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">
                    Laravel Companion
                </h1>
                <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
                    A structured JSON API for mobile and external tooling to inspect, monitor, and manage Laravel applications. This demo app exercises every feature of the package.
                </p>
                <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm dark:border-amber-900 dark:bg-amber-900/20">
                    <p class="font-medium text-amber-800 dark:text-amber-300">Demo credentials</p>
                    <div class="mt-1.5 space-y-0.5 font-mono text-amber-700 dark:text-amber-400">
                        <p>Email: <span class="select-all">test@example.com</span></p>
                        <p>Password: <span class="select-all">password</span></p>
                    </div>
                    <p class="mt-2 text-amber-600 dark:text-amber-500">This is a live demo. The database resets every 6 hours — feel free to explore.</p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="https://github.com/clinically-au/laravel-companion" target="_blank" class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                        Package Source
                    </a>
                    <a href="https://github.com/clinically-au/laravel-companion-demo" target="_blank" class="inline-flex items-center gap-2 rounded-md border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-750">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                        Demo Source
                    </a>
                </div>
            </div>

            {{-- Features Grid --}}
            <div class="mt-20">
                <h2 class="text-2xl font-bold">Features Demonstrated</h2>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">Every feature below is fully integrated, configured, and tested in this demo application.</p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @php
                        $features = [
                            ['Agent Authentication', 'Token-based auth with scopes, IP allowlists, expiry, and revocation.'],
                            ['Model Browser', 'Discover models, browse records with filtering, sorting, search, and CompanionSerializable.'],
                            ['Route Introspection', 'List all registered routes with filtering by method, name, URI, and middleware.'],
                            ['Command Execution', 'List and execute whitelisted Artisan commands. Blacklist always blocks dangerous ones.'],
                            ['Queue Management', 'Monitor queues, view/retry/delete failed jobs, and flush the queue.'],
                            ['Cache Inspection', 'Read, forget, and flush cache keys. Prefix allowlists for production safety.'],
                            ['Config Viewer', 'Browse the full config tree. Sensitive values are automatically redacted.'],
                            ['Log Viewer', 'Parse log entries with level filtering, search, and SSE live-tail streaming.'],
                            ['Schedule Viewer', 'See all scheduled commands with cron expressions and next-due dates.'],
                            ['Migration History', 'View migration history grouped by batch with totals.'],
                            ['Event Map', 'See all events and their listeners, including queued listener detection.'],
                            ['Admin Dashboard', 'Built-in Livewire dashboard to manage agents, audit logs, and features.'],
                            ['Audit Logging', 'Every API request logged with agent, method, path, response code, IP, and duration.'],
                            ['Custom Features', 'Register your own endpoints via Companion::registerFeature().'],
                            ['Artisan Commands', '7 CLI commands: install, agent, pair, agents, revoke, prune-audit, status.'],
                            ['HasCompanionAccess', 'User trait for creating/revoking agents, checking gate access, and querying audit logs.'],
                            ['Rate Limiting', 'Per-IP throttling for API (120/min), SSE (5/min), and command execution.'],
                            ['Environment Info', 'App name, environment, debug flag, drivers, PHP and Laravel versions.'],
                        ];
                    @endphp

                    @foreach ($features as [$title, $description])
                        <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-800">
                            <h3 class="font-semibold">{{ $title }}</h3>
                            <p class="mt-1.5 text-sm text-zinc-600 dark:text-zinc-400">{{ $description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Demo Models --}}
            <div class="mt-20">
                <h2 class="text-2xl font-bold">Demo Data</h2>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">The app includes models and seed data to exercise the model browser and relationship features.</p>

                <div class="mt-6 overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-zinc-200 bg-zinc-100/50 dark:border-zinc-800 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-3 font-medium">Model</th>
                                <th class="px-4 py-3 font-medium">CompanionSerializable</th>
                                <th class="px-4 py-3 font-medium">Relationships</th>
                                <th class="px-4 py-3 font-medium">Notable</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            <tr>
                                <td class="px-4 py-3 font-medium">User</td>
                                <td class="px-4 py-3 text-zinc-500">No</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">posts, comments, companionAgents</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">HasCompanionAccess trait</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Post</td>
                                <td class="px-4 py-3 text-emerald-600 dark:text-emerald-400">Yes</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">user, comments, tags</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">SoftDeletes, published/draft scopes</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Comment</td>
                                <td class="px-4 py-3 text-zinc-500">No</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">post, user</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">approved scope</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Tag</td>
                                <td class="px-4 py-3 text-emerald-600 dark:text-emerald-400">Yes</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">posts (many-to-many)</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">Minimal serialization</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Test Coverage --}}
            <div class="mt-20">
                <h2 class="text-2xl font-bold">Test Coverage</h2>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">121 passing tests (88 companion + 33 auth/settings) covering every API endpoint, dashboard page, artisan command, and trait method.</p>

                <div class="mt-6 flex flex-wrap gap-2">
                    @php
                        $testFiles = [
                            'Authentication', 'ScopeEnforcement', 'Environment', 'ModelDiscovery', 'ModelBrowsing',
                            'RouteIntrospection', 'Command', 'Queue', 'Cache', 'Config', 'Log', 'Schedule',
                            'Migration', 'Event', 'Dashboard', 'Audit', 'Capabilities', 'RateLimit',
                            'CustomFeature', 'HasCompanionAccess', 'ArtisanCommand',
                        ];
                    @endphp
                    @foreach ($testFiles as $test)
                        <span class="inline-flex items-center gap-1 rounded-md border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:border-green-900 dark:bg-green-900/20 dark:text-green-400">
                            <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            {{ $test }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Quick Start --}}
            <div class="mt-20">
                <h2 class="text-2xl font-bold">Quick Start</h2>
                <div class="mt-6 space-y-4 rounded-lg border border-zinc-200 bg-zinc-900 p-6 font-mono text-sm text-zinc-100 dark:border-zinc-800">
                    <div><span class="text-zinc-500"># Clone and set up</span></div>
                    <div>git clone https://github.com/clinically-au/laravel-companion-demo.git</div>
                    <div>cd laravel-companion-demo</div>
                    <div>composer setup</div>
                    <div>&nbsp;</div>
                    <div><span class="text-zinc-500"># Seed demo data</span></div>
                    <div>php artisan db:seed</div>
                    <div>&nbsp;</div>
                    <div><span class="text-zinc-500"># Run the tests</span></div>
                    <div>php artisan test</div>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-zinc-200 dark:border-zinc-800">
            <div class="mx-auto max-w-5xl px-6 py-8 text-center text-sm text-zinc-500">
                Built by <a href="https://github.com/clinically-au" target="_blank" class="font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100">Clinically</a> to demonstrate the Laravel Companion package.
            </div>
        </footer>
    </body>
</html>
