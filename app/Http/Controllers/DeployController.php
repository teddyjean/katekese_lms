<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    public function migrate(Request $request)
    {
        $token = (string) $request->bearerToken();
        $expected = (string) config('app.deploy_migrate_token');

        if ($expected === '' || ! hash_equals($expected, $token)) {
            abort(403);
        }

        Artisan::call('migrate', ['--force' => true]);

        return response(Artisan::output(), 200)->header('Content-Type', 'text/plain');
    }
}
