<?php

namespace PortedCheese\BaseSettings\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user()) {
            return redirect('login');
        }
        $exploded = explode("|", $role);
        $condition = FALSE;
        foreach ($exploded as $item) {
            $condition |= $request->user()->hasRole($item);
        }
        if (! $condition) {
            throw new AuthenticationException();
//            abort(403);
        }
        return $next($request);
    }
}
