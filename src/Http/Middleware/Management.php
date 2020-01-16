<?php

namespace PortedCheese\BaseSettings\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class Management
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
        if (! Gate::allows("site-management")) {
            abort(403, trans('Доступ запрещен.'));
        }
        return $next($request);
    }
}
