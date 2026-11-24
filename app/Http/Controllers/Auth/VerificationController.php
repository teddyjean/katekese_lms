<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->to($this->homeRoute($request));
        }

        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->to($this->homeRoute($request));
        }

        $request->fulfill();

        return redirect()->to($this->homeRoute($request))
            ->with('success', 'Email berhasil diverifikasi.');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->to($this->homeRoute($request));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi baru sudah dikirim ke email Anda.');
    }

    private function homeRoute(Request $request): string
    {
        return match ($request->user()->role) {
            'katekis' => route('admin.dashboard'),
            default => route('peserta.dashboard'),
        };
    }
}
