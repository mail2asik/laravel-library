<?php namespace App\Http\Middleware;

use Closure;
use helpers;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!helpers::isLoggedIn()) {

            //Store requested url in session & redirect to this page after successful login
            $request->session()->put('intended_url', $request->fullUrl());

            return redirect()->guest('login');
        }

        $user = helpers::getUser();
        if ($user['role']['slug'] == 'member') {
            return redirect('dashboard');
        }

        return $next($request);
    }

}
