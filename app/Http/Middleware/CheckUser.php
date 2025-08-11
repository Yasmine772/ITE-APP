<?php



namespace App\Http\Middleware;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class CheckUser
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('teacher'))
        return $next($request);
        return $this->errorResponse('Unauthorized','Not having access' ,403);
    }
}
