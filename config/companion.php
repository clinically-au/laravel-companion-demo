<?php

declare(strict_types=1);
use Clinically\Companion\Models\CompanionAgent;
use Clinically\Companion\Models\CompanionAuditLog;

return [

    /*
    |--------------------------------------------------------------------------
    | Route Prefix & Domain
    |--------------------------------------------------------------------------
    */
    'path' => env('COMPANION_PATH', 'companion'),

    'domain' => env('COMPANION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    | The middleware stack applied to all Companion API routes.
    | The dashboard routes additionally receive 'web' and the
    | auth gate below.
    */
    'middleware' => ['throttle:companion'],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Auth (Horizon-style)
    |--------------------------------------------------------------------------
    | In local environment the dashboard is open. In production
    | the gate below must return true.
    */
    'auth' => [
        'gate' => 'viewCompanion',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    */
    'user_model' => env('COMPANION_USER_MODEL', 'App\\Models\\User'),

    /*
    |--------------------------------------------------------------------------
    | Agent Token Settings
    |--------------------------------------------------------------------------
    */
    'agents' => [
        'token_prefix' => 'cmp_',
        'default_expiry_days' => 90,
        'max_agents' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Scopes
    |--------------------------------------------------------------------------
    | Every scope the package understands. Agents are granted a
    | subset at creation. Scopes are only enforceable for features
    | that are enabled below — disabling a feature implicitly
    | revokes the scope regardless of what the agent holds.
    */
    'scopes' => [
        'models:read',
        'models:browse',
        'routes:read',
        'commands:list',
        'commands:execute',
        'queues:read',
        'queues:write',
        'cache:read',
        'cache:write',
        'config:read',
        'logs:read',
        'schedule:read',
        'migrations:read',
        'events:read',
        'environment:read',
        'horizon:read',
        'horizon:write',
        'pulse:read',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Scope Presets
    |--------------------------------------------------------------------------
    */
    'scope_presets' => [
        'read-only' => [
            'models:read', 'routes:read', 'commands:list',
            'queues:read', 'cache:read', 'config:read',
            'logs:read', 'schedule:read', 'migrations:read',
            'events:read', 'environment:read',
        ],
        'operator' => ['*:read', 'queues:write', 'cache:write', 'commands:execute'],
        'admin' => ['*'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features (Enable/Disable Endpoint Groups)
    |--------------------------------------------------------------------------
    | Master switches for each feature group. When a feature is
    | disabled, its routes are never registered, its scopes are
    | inert, and the /capabilities endpoint reports it as
    | unavailable.
    |
    | Each feature can be:
    |   true     — enabled
    |   false    — disabled, routes not registered
    |   array    — feature enabled with sub-feature control
    */
    'features' => [

        'environment' => true,

        'models' => [
            'enabled' => true,
            'browse' => true,
        ],

        'routes' => true,

        'commands' => [
            'enabled' => true,
            'execute' => true,
        ],

        'queues' => [
            'enabled' => true,
            'write' => true,
        ],

        'cache' => [
            'enabled' => true,
            'read' => true,
            'write' => true,
        ],

        'config' => true,

        'logs' => [
            'enabled' => true,
            'stream' => true,
        ],

        'schedule' => true,

        'migrations' => true,

        'events' => true,

        'horizon' => [
            'enabled' => true,
            'write' => true,
        ],

        'pulse' => [
            'enabled' => true,
        ],

        'telescope' => [
            'enabled' => false,
        ],

        'dashboard' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Whitelisted Artisan Commands
    |--------------------------------------------------------------------------
    | Only commands in this list can be executed via the API when
    | features.commands.execute is true and the agent holds the
    | commands:execute scope.
    */
    'whitelisted_commands' => [
        'cache:clear',
        'config:clear',
        'view:clear',
        'route:clear',
        'event:clear',
        'queue:restart',
        'horizon:pause',
        'horizon:continue',
        'horizon:terminate',
        'optimize:clear',
        'inspire',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blacklisted Commands (can never be whitelisted)
    |--------------------------------------------------------------------------
    */
    'blacklisted_commands' => [
        'migrate',
        'migrate:fresh',
        'migrate:reset',
        'migrate:rollback',
        'db:wipe',
        'db:seed',
        'key:generate',
        'tinker',
        'env',
        'down',
        'up',
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Browser
    |--------------------------------------------------------------------------
    */
    'models' => [
        'paths' => ['app/Models'],
        'max_per_page' => 100,
        'default_per_page' => 25,
        'eager_load_depth' => 1,
        'hidden_columns' => [
            'password', 'remember_token', 'token_hash',
            'two_factor_secret', 'two_factor_recovery_codes',
        ],
        'redact_patterns' => ['/password/i', '/secret/i', '/token/i', '/key/i'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Config Redaction
    |--------------------------------------------------------------------------
    */
    'config_redaction' => [
        'patterns' => ['/password/i', '/secret/i', '/key$/i', '/token/i', '/private/i', '/credential/i'],
        'always_redact' => [
            'app.key',
            'database.connections.*.password',
            'mail.mailers.*.password',
            'services.*.secret',
        ],
        'never_redact' => [
            'app.name', 'app.env', 'app.debug',
            'app.url', 'app.timezone',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Browser
    |--------------------------------------------------------------------------
    | When allowed_prefixes is set, only cache keys matching one of the
    | prefixes can be read/deleted via the API. Empty array allows all keys.
    */
    'cache' => [
        'allowed_prefixes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Viewer
    |--------------------------------------------------------------------------
    */
    'logs' => [
        'path' => storage_path('logs'),
        'max_file_size_mb' => 50,
        'tail_lines' => 500,
        'sse_enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Log
    |--------------------------------------------------------------------------
    */
    'audit' => [
        'enabled' => true,
        'retention_days' => 90,
        'log_reads' => true,
        'log_writes' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'api' => 120,
        'sse' => 5,
        'commands' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Models & Tables (for config-driven resolution)
    |--------------------------------------------------------------------------
    */
    'models_map' => [
        'agent' => CompanionAgent::class,
        'audit_log' => CompanionAuditLog::class,
    ],

    'tables' => [
        'agents' => 'companion_agents',
        'audit_logs' => 'companion_audit_logs',
    ],
];
