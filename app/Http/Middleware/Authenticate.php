<?php

namespace App\Http\Middleware;

use Closure;
use helpers;

class Authenticate
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
        if (!helpers::isLoggedIn()) {

            //Store requested url in session & redirect to this page after successful login
            $request->session()->put('intended_url', $request->fullUrl());

            return redirect()->guest('login')->with('flash_message', [
                'status'       => 'fail',
                'code'         => '10000',
                'message'      => 'You need to login to perform this action',
                'error_fields' => ''
            ]);
        }

        $user = helpers::getUser();
        if ($user['role']['slug'] != 'member') {
            return redirect('admin/dashboard');
        }

        return $next($request);
    }
}
