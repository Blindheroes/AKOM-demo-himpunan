<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Models\Letter;
use App\Models\Lpj;
use App\Models\News;
use App\Models\Document;
use App\Models\Gallery;
use App\Policies\EventPolicy;
use App\Policies\LetterPolicy;
use App\Policies\LpjPolicy;
use App\Policies\NewsPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\GalleryPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Letter::class => LetterPolicy::class,
        Lpj::class => LpjPolicy::class,
        News::class => NewsPolicy::class,
        Document::class => DocumentPolicy::class,
        Gallery::class => GalleryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define additional gates as needed
        Gate::define('manage-departments', function ($user) {
            return in_array($user->role, ['executive', 'admin']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-settings', function ($user) {
            return $user->role === 'admin';
        });
    }
}
