<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <flux:heading size="xl">Laravel Companion Demo</flux:heading>
            <flux:text class="mt-1">This app demonstrates every feature of the <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-sm dark:bg-zinc-700">clinically/laravel-companion</code> package.</flux:text>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            {{-- Admin Dashboard --}}
            <a href="/companion/dashboard" class="group flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 transition hover:border-zinc-400 dark:border-zinc-700 dark:hover:border-zinc-500">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <flux:icon.shield-check class="size-5" />
                    </div>
                    <flux:heading size="lg">Admin Dashboard</flux:heading>
                </div>
                <flux:text>Manage agents, view audit logs, and check feature status via the built-in Livewire dashboard.</flux:text>
            </a>

            {{-- Agent Management --}}
            <a href="/companion/dashboard/agents" class="group flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 transition hover:border-zinc-400 dark:border-zinc-700 dark:hover:border-zinc-500">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <flux:icon.key class="size-5" />
                    </div>
                    <flux:heading size="lg">Agent Auth</flux:heading>
                </div>
                <flux:text>Token-based authentication with scopes, IP allowlists, expiry, and revocation. Create agents to test the API.</flux:text>
            </a>

            {{-- Model Browsing --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400">
                        <flux:icon.cube class="size-5" />
                    </div>
                    <flux:heading size="lg">Model Browser</flux:heading>
                </div>
                <flux:text>Discover models, browse records with filtering/sorting/search, and inspect relationships. Post and Tag implement CompanionSerializable.</flux:text>
                <div class="mt-auto flex flex-wrap gap-1.5">
                    <flux:badge size="sm" color="zinc">User</flux:badge>
                    <flux:badge size="sm" color="zinc">Post</flux:badge>
                    <flux:badge size="sm" color="zinc">Comment</flux:badge>
                    <flux:badge size="sm" color="zinc">Tag</flux:badge>
                </div>
            </div>

            {{-- Route Introspection --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                        <flux:icon.map class="size-5" />
                    </div>
                    <flux:heading size="lg">Routes</flux:heading>
                </div>
                <flux:text>Introspect all registered routes with filtering by method, name, URI, middleware, and controller.</flux:text>
            </div>

            {{-- Commands --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400">
                        <flux:icon.command-line class="size-5" />
                    </div>
                    <flux:heading size="lg">Commands</flux:heading>
                </div>
                <flux:text>List and execute whitelisted Artisan commands via the API. Blacklisted commands are always blocked.</flux:text>
            </div>

            {{-- Queues --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400">
                        <flux:icon.queue-list class="size-5" />
                    </div>
                    <flux:heading size="lg">Queues</flux:heading>
                </div>
                <flux:text>Monitor queue connections, view failed jobs, retry or flush them. Includes a demo ProcessPostAnalytics job.</flux:text>
            </div>

            {{-- Cache --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-orange-50 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                        <flux:icon.circle-stack class="size-5" />
                    </div>
                    <flux:heading size="lg">Cache</flux:heading>
                </div>
                <flux:text>Inspect cache driver info, read/forget keys, and flush the store. Supports prefix allowlists for production safety.</flux:text>
            </div>

            {{-- Config --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
                        <flux:icon.cog-6-tooth class="size-5" />
                    </div>
                    <flux:heading size="lg">Config</flux:heading>
                </div>
                <flux:text>View the full config tree or specific keys. Sensitive values like passwords and keys are automatically redacted.</flux:text>
            </div>

            {{-- Logs --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-pink-50 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400">
                        <flux:icon.document-text class="size-5" />
                    </div>
                    <flux:heading size="lg">Logs</flux:heading>
                </div>
                <flux:text>List log files, parse entries with level filtering and search. Includes SSE live-tail streaming.</flux:text>
            </div>

            {{-- Schedule, Migrations, Events --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                        <flux:icon.clock class="size-5" />
                    </div>
                    <flux:heading size="lg">Schedule, Migrations & Events</flux:heading>
                </div>
                <flux:text>View scheduled commands with next-due dates, migration history by batch, and the full event/listener map.</flux:text>
            </div>

            {{-- Audit Log --}}
            <a href="/companion/dashboard/audit" class="group flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 transition hover:border-zinc-400 dark:border-zinc-700 dark:hover:border-zinc-500">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                        <flux:icon.clipboard-document-list class="size-5" />
                    </div>
                    <flux:heading size="lg">Audit Log</flux:heading>
                </div>
                <flux:text>Every API request is logged with agent, action, method, path, response code, IP, and duration.</flux:text>
            </a>

            {{-- Custom Features --}}
            <div class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600 dark:bg-fuchsia-900/30 dark:text-fuchsia-400">
                        <flux:icon.puzzle-piece class="size-5" />
                    </div>
                    <flux:heading size="lg">Custom Features</flux:heading>
                </div>
                <flux:text>Register your own API endpoints via <code class="rounded bg-zinc-100 px-1 text-sm dark:bg-zinc-700">Companion::registerFeature()</code>. This demo registers a <code class="rounded bg-zinc-100 px-1 text-sm dark:bg-zinc-700">demo-stats</code> endpoint.</flux:text>
            </div>
        </div>

        {{-- Test Coverage --}}
        <div class="rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
            <div class="flex items-center gap-3">
                <div class="flex size-10 items-center justify-center rounded-lg bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                    <flux:icon.check-badge class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">121 Tests Passing</flux:heading>
                    <flux:text>88 companion feature tests + 33 auth/settings tests covering every API endpoint, dashboard page, artisan command, and trait method.</flux:text>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
