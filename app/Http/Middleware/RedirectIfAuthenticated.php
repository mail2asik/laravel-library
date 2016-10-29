<?php

namespace App\Http\Middleware;

use Closure;
use helpers;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (helpers::isLoggedIn()) {

            $user = helpers::getUser();

            if ($user['role']['slug'] != 'member') {
                return redirect('admin/dashboard');
            } else {
                return redirect('dashboard');
            }
        }

        return $next($request);
    }
}
