<?php
/*
 * Author: WOLF
 * Name: TraccarMigration.php
 * Modified : mar., 13 févr. 2024 12:35
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Support;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TraccarMigration extends Migration
{
    /**
     * Determine if the migration should run.
     */
    protected function shouldRun(): bool
    {
        if (in_array($this->driver(), ['mysql', 'pgsql', 'sqlite'])) {
            return true;
        }

        if (!App::environment('testing')) {
            throw new RuntimeException("Traccar does not support the [{$this->driver()}] database driver.");
        }

        if (Config::get('traccar.devices.store_in_database')) {
            throw new RuntimeException("Traccar does not support the [{$this->driver()}] database driver. You can disable Traccar device store in your testsuite by adding `<env name=\"TRACCAR_AUTO_SAVE_DEVICES\" value=\"false\"/>` to your project's `phpunit.xml` file.");
        }

        return false;
    }

    /**
     * Get the database connection driver.
     */
    protected function driver(): string
    {
        return DB::connection($this->getConnection())->getDriverName();
    }

    /**
     * Get the migration connection name.
     */
    public function getConnection(): ?string
    {
        $res = Config::get('traccar.database.connection');
        return is_string($res) ? $res : null;
    }

}
