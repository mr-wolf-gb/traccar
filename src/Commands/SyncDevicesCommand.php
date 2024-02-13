<?php
/*
 * Author: WOLF
 * Name: SyncDevicesCommand.php
 * Modified : mar., 13 févr. 2024 14:47
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Log;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Device;
use MrWolfGb\Traccar\Services\TraccarService;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @internal
 */
#[AsCommand(name: 'traccar:sync-devices')]
class SyncDevicesCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The command's signature.
     *
     * @var string
     */
    public $signature = 'traccar:sync-devices';

    /**
     * The command's description.
     *
     * @var string
     */
    public $description = 'Sync local stored devices with traccar server';

    /**
     * The console command name aliases.
     *
     * @var array<int, string>
     */
    protected $aliases = ['traccar:sync'];

    /**
     * Handle the command.
     */
    public function handle(TraccarService $service): int
    {
        if (!$this->confirmToProceed()) {
            return Command::FAILURE;
        }

        try {
            foreach ($service->deviceRepository()->fetchListDevices() as $key => $device) {
                if ($device instanceof Device) {
                    $device->createOrUpdate();
                }
            }
        } catch (TraccarException $e) {
            Log::critical($e->getMessage());
            $this->error("Synchronization operation failed : " . $e->getMessage());
            return Command::FAILURE;
        }
        $this->info("Synchronization operation successful : " . Device::count() . " Devices in database");
        return Command::SUCCESS;
    }
}
