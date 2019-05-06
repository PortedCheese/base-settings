<?php

namespace PortedCheese\BaseSettings\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param   string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()) {
            return redirect('login');
        }
        $exploded = explode("|", $role);
        $condition = FALSE;
        foreach ($exploded as $item) {
            $condition |= $request->user()->hasRole($item);
        }
        if (!$condition) {
            abort(403);
        }
        return $next($request);
    }
}
