<?php

   namespace App\Http\Middleware;

   use Closure;
   use Illuminate\Support\Facades\Gate;

   class AuthorizeAdmin
   {
       public function handle($request, Closure $next)
       {
           if (Gate::denies('isAdmin')) {
               abort(403, 'Unauthorized');
           }

           return $next($request);
       }
   }