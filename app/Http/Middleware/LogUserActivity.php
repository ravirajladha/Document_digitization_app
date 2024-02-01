<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next) :Response
    {
        // Continue request execution
        $response = $next($request);

        if (Auth::check()) { // Check if the user is authenticated
            // Format the log data
            $logData = [
                'user_id' => Auth::id(),
                'action' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ];

            // Log the activity
            Log::channel('useractivity')->info('User Activity:', $logData);
        }

        return $response;
    }

}

//create a new middleware
//add the middleware into the kernel.php
// add into logging.php
