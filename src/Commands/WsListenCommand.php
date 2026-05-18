<?php

/*
 * Author: WOLF
 * Name: WsListenCommand.php
 * Modified : ven., 23 févr. 2024 14:29
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Services\TraccarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;
use WebSocket\Client;
use WebSocket\Configuration;

/**
 * @internal
 */
#[AsCommand(name: 'traccar:listen')]
class WsListenCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The command's signature.
     *
     * @var string
     */
    public $signature = 'traccar:listen {--ws= : Custom websocket URL, defaults to traccar.websocket_url or <base_url>/api/socket}
                                        {--log : show console logs}';

    /**
     * The command's description.
     *
     * @var string
     */
    public $description = 'Listen to traccar websocket server';

    /**
     * The console command name aliases.
     *
     * @var array<int, string>
     */
    protected $aliases = ['traccar:ws-listen'];

    /**
     * Handle the command.
     *
     * @throws TraccarException
     */
    public function handle(TraccarService $service, ConfigRepository $config): int
    {
        $sessionCookie = $service->sessionRepository()->getCookies();
        $cookieHeader = $sessionCookie->getName().'='.$sessionCookie->getValue();
        $websocketOption = $this->option('ws');
        $configuredWebsocketUrl = $config->get('traccar.websocket_url');
        $wsUrl = $this->resolveWebsocketUrl(
            is_string($websocketOption) && $websocketOption !== ''
                ? $websocketOption
                : (is_string($configuredWebsocketUrl) ? $configuredWebsocketUrl : null),
            $service->getBaseUrl()
        );

        $this->info('Connecting to: '.$wsUrl);
        $this->line('Using cookie: '.$cookieHeader);

        $client = new Client($wsUrl, new Configuration(timeout: 60));
        $client->addHeader('Cookie', $cookieHeader);

        try {
            $client->connect();

            while ($client->isConnected()) {
                $message = $client->receive();
                $data = $message->getContent();

                if ($data === '') {
                    continue;
                }

                $dataObject = json_decode($data, true);
                if (is_array($dataObject) && array_key_exists('positions', $dataObject)) {
                    $this->info('dispatch Event positions');
                } elseif (is_array($dataObject) && array_key_exists('events', $dataObject)) {
                    $this->info('dispatch Event event');
                } else {
                    $this->info('dispatch Event unknown');
                }

                if ($this->option('log')) {
                    $this->line($data);
                }
            }
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        } finally {
            if ($client->isConnected()) {
                try {
                    $client->close();
                } catch (Throwable) {
                }
            }
        }

        return self::SUCCESS;
    }

    private function resolveWebsocketUrl(?string $websocketUrl, string $baseUrl): string
    {
        if (! empty($websocketUrl)) {
            return $websocketUrl;
        }

        $url = rtrim($baseUrl, '/').'/api/socket';

        return preg_replace('/^http/i', 'ws', $url) ?? $url;
    }
}
