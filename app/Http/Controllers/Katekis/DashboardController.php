<?php

namespace App\Http\Controllers\Katekis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $batches = Auth::user()->batchesAsKatekis()->with('program')->where('status', 'active')->get();

        return view('katekis.dashboard', compact('batches'));
    }
}
