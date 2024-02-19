<?php
/*
 * Author: WOLF
 * Name: TraccarSessionMiddleware.php
 * Modified : lun., 19 févr. 2024 08:17
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use MrWolfGb\Traccar\Services\TraccarService;

final class TraccarSessionMiddleware
{

    public function __construct(public TraccarService $traccarService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($cookies = $this->traccarService->sessionRepository()->getCookies()) {
            View::share('traccarSessionId', explode('.', $cookies->getValue())[0]);
        }
        return $next($request);
    }
}
