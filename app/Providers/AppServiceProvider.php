<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Carbon\CarbonImmutable;
use Clinically\Companion\Companion;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerCompanionFeatures();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureCompanionGate();
    }

    protected function registerCompanionFeatures(): void
    {
        Companion::registerFeature('demo-stats', function (Router $router) {
            $router->get('/demo-stats', function () {
                return response()->json([
                    'data' => [
                        'total_users' => User::count(),
                        'total_posts' => Post::count(),
                        'total_comments' => Comment::count(),
                        'total_tags' => Tag::count(),
                    ],
                ]);
            })->name('companion.api.demo-stats');
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    protected function configureCompanionGate(): void
    {
        Gate::define('viewCompanion', function (?User $user = null) {
            return $user !== null;
        });
    }
}
