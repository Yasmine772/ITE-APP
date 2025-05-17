<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class verifiedEmail
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user() || (!$request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail()))
        {
            return $this-> errorResponse($message = 'Your email address is not verified', $error = null, $code = 500);
        }

        return $next($request);
    }
}
