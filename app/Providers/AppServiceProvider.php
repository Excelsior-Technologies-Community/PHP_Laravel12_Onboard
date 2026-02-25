<?php

namespace App\Providers;
use Spatie\Onboard\Facades\Onboard;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


    public function boot(): void
    {
        Onboard::addStep('Complete Profile')
            ->link('/profile')
            ->cta('Complete Profile')
            ->completeIf(function (User $model) {
                return optional($model->profile)->completed == true;
            });

        Onboard::addStep('Create First Post')
            ->link('/post/create')
            ->cta('Create Post')
            ->completeIf(function (User $user) {
                return $user->posts()->exists(); // ✅ Mark complete if user has any posts
            });
    }
}
