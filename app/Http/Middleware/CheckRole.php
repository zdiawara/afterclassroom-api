<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Exceptions\BadRoleException;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->userOrFail();
        if(is_null($user) || $role != $user->userable_type){
            throw new BadRoleException("Rôle insuffusant");
        }
        return $next($request);
    }
}
