<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('system_settings', function (): ?Setting {
            try {
                if (isDBConnected() && Schema::hasTable('settings')) {
                    return Setting::where('type', 'system')->first();
                }

                return null;
            } catch (\Throwable $th) {
                return null;
            }
        });

        $this->app->singleton('intro_page', function (): ?Page {
            try {
                if (isDBConnected() && Schema::hasTable('settings')) {
                    $home = Setting::where('type', 'home_page')->first();

                    $page = Page::where('id', $home->fields['page_id'])
                        ->with(['sections' => function ($query) {
                            $query->orderBy('sort', 'asc');
                        }])
                        ->first();

                    return $page;
                }

                return null;
            } catch (\Throwable $th) {
                return null;
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return env('FRONTEND_URL') . '/reset-password?token=' . $token . '&email=' . $user->email;
        });
    }
}
