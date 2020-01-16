<?php

namespace PortedCheese\BaseSettings\Http\Middleware;

use Closure;

class SuperUser
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()) {
            return redirect('login');
        }
        $user = $request->user();
        if (! $user->isSuperUser()) {
            abort(403, trans('Forbidden.'));
        }
        return $next($request);
    }
}
