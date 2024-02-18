<?php

namespace MrWolfGb\Traccar\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MrWolfGb\Traccar\Services\TraccarService;

final class TraccarSessionMiddleware
{

    public function __construct(public TraccarService $traccarService)
    {
    }
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);
        if ($cookies = $this->traccarService->sessionRepository()->getCookies())
            return $response->withCookie($cookies);
        else
            return $response;
    }
}
