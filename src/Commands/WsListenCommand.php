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
use MrWolfGb\Traccar\Services\TraccarService;
use Symfony\Component\Console\Attribute\AsCommand;
//use WebSocket\Client;
//use WebSocket\ConnectionException;

/**
 * @internal
 */
#[AsCommand(name: 'traccar:listen')]
class WsListenCommand extends Command
{
    use ConfirmableTrait;

    /**
     * This just implementation to read ws with package "textalk/websocket": "^1.5"
     *
     * The command's signature.
     *
     * @var string
     */
    public $signature = 'traccar:listen {--ws=* : Custom websocket host}
                                        {--session-id=* : authenticated session ID}
                                        {--token=* : use access token}
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
     */
    public function handle(TraccarService $service): int
    {
//        $wsUrl = config('traccar.websocket_url');
//        if ($this->option('ws')) {
//            $wsUrl = $this->option('ws');
//        }
//        $sessionCookies = $service->sessionRepository()->getCookies();
//
//        if ($this->option('session-id')) {
//            $wsUrl .= '?session=' . $this->option('session-id');
//        } elseif ($sessionCookies->getValue()) {
//            $sessionID = explode('.', $sessionCookies->getValue())[0];
//            $wsUrl .= '?session=' . $sessionID;
//        } elseif ($this->option('token')) {
//            $wsUrl .= '?token=' . $this->option('token');
//        } elseif (!empty($service->getToken())) {
//            $wsUrl .= '?token=' . $service->getToken();
//        } else {
//            $this->error("No session or token provided");
//            return self::FAILURE;
//        }
//        $this->info("Connecting to: " . $wsUrl);
//
//        $client = new Client(
//            uri: $wsUrl,
//            options: [
//                'filter' => ['text'],
//                'headers' => [
//                    "Cookie" => $sessionCookies->getName() . "=" . $sessionCookies->getValue()
//                ],
//                'timeout' => 60,
//            ],
//        );
//        while (true) {
//            try {
//                $data = $client->receive();
//                if (strlen($data) > 2) {
//                    $dataObject = json_decode($data, true);
//                    if (array_key_exists('positions', $dataObject)) {
//                        $this->info("dispatch Event positions");
//                    } elseif (array_key_exists('events', $dataObject)) {
//                        $this->info("dispatch Event event");
//                    } else {
//                        $this->info("dispatch Event unknown");
//                    }
//                    if ($this->option('log')) {
//                        $this->info($data);
//                    }
//                }
//            } catch (ConnectionException $e) {
//                $this->error($e->getMessage());
//                break;
//            }
//        }
//        $client->close();
        return self::SUCCESS;
    }
}
