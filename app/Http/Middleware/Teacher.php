<?php

namespace App\Http\Middleware;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Teacher
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->role  === 'Teacher'){
            return $next($request);
        }
        return $this->errorResponse('Unauthorized', 'Not having access',403);
    }
}
