<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareNotifications
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $unreadNotifications = auth()->user()->unreadNotifications()->take(5)->get();
            $unreadCount = auth()->user()->unreadNotifications()->count();
            view()->share('unreadNotifications', $unreadNotifications);
            view()->share('unreadCount', $unreadCount);
        }
        return $next($request);
    }
}
