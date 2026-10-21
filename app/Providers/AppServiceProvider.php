<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Administrator punya akses penuh ke semua Gate/Policy (global allowance).
        Gate::before(function ($user) {
            return $user->isAdministrator() ? true : null;
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email')
                ->greeting('Halo!')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
                ->action('Verifikasi Alamat Email', $url)
                ->line('Jika Anda tidak membuat akun ini, Anda dapat mengabaikan email ini.')
                ->salutation("Salam,\n".config('app.name'));
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]);

            return (new MailMessage)
                ->subject('Reset Password')
                ->greeting('Halo!')
                ->line('Kami menerima permintaan reset password untuk akun Anda.')
                ->action('Reset Password', $url)
                ->line('Link ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini.')
                ->salutation("Salam,\n".config('app.name'));
        });

        View::composer('layouts.app', function ($view) {
            $pendingCount = 0;

            if (auth()->check() && auth()->user()->isStaff()) {
                $pendingCount = DB::table('batch_participants')
                    ->where('status', 'pending')
                    ->count();
            }

            $view->with('pendingEnrollmentCount', $pendingCount);
        });
    }
}
