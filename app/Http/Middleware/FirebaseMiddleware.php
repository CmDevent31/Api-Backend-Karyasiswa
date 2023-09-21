<?php

namespace App\Http\Middleware;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Closure;

class FirebaseMiddleware
{
    public function handle($request, Closure $next)
    {
        // Inisialisasi Firebase Admin SDK dengan konfigurasi
        $serviceAccount = ServiceAccount::fromJsonFile(storage_path('path/to/your-firebase-admin-sdk.json'));
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://backend-karya-default-rtdb.firebaseio.com')
            ->create();

        // Simpan Firebase di session atau request jika diperlukan
        $request->attributes->set('firebase', $firebase);

        return $next($request);
    }
}

