<?php

/*
 * Author: WOLF
 * Name: TraccarSessionMiddleware.php
 * Modified : mar., 27 févr. 2024 12:23
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use MrWolfGb\Traccar\Services\TraccarService;
use Symfony\Component\HttpFoundation\Response;

final class TraccarSessionMiddleware
{
    public function __construct(public TraccarService $traccarService, public ConfigRepository $config) {}

    public function handle(Request $request, Closure $next): Response
    {
        $cookies = $this->traccarService->sessionRepository()->getCookies();
        View::share('traccarSessionId', explode('.', $cookies->getValue() ?? '')[0]);
        $response = $next($request);

        if ($this->config->get('traccar.websocket_cookie.enabled', true)) {
            $domain = $this->config->get('traccar.websocket_cookie.domain');
            $response->headers->setCookie(
                $cookies
                    ->withDomain(is_string($domain) && $domain !== '' ? $domain : null)
                    ->withSameSite($this->cookieSameSite())
                    ->withSecure((bool) $this->config->get('traccar.websocket_cookie.secure', false))
                    ->withHttpOnly((bool) $this->config->get('traccar.websocket_cookie.http_only', true))
            );
        }

        return $response;
    }

    /**
     * @return 'lax'|'strict'|'none'|null
     */
    private function cookieSameSite(): ?string
    {
        $sameSite = $this->config->get('traccar.websocket_cookie.same_site', 'lax');
        if (! is_string($sameSite) || $sameSite === '') {
            return null;
        }

        $sameSite = strtolower($sameSite);

        return match ($sameSite) {
            'strict' => 'strict',
            'none' => 'none',
            default => 'lax',
        };
    }
}
