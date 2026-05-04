<?php

namespace App\Providers;

use App\Models\Consultation;
use App\Models\Message;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\User;
use App\Policies\ConsultationPolicy;
use App\Policies\MessagePolicy;
use App\Policies\PatientPolicy;
use App\Policies\RendezVousPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(RendezVous::class, RendezVousPolicy::class);
        Gate::policy(Consultation::class, ConsultationPolicy::class);
        Gate::policy(Message::class, MessagePolicy::class);

        view()->composer('*', function ($view) {
            $pendingMedecinsCount = 0;
            $unreadNotificationsCount = 0;

            if (auth()->check()) {
                if (auth()->user()->isAdmin()) {
                    $pendingMedecinsCount = User::where('role', 'medecin')
                        ->where('is_active', false)
                        ->count();
                }

                $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
            }

            $view->with([
                'pendingMedecinsCount' => $pendingMedecinsCount,
                'unreadNotificationsCount' => $unreadNotificationsCount,
            ]);
        });
    }
}
