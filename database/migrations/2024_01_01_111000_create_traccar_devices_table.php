<?php
/*
 * Author: WOLF
 * Name: 2024_01_01_111000_create_traccar_devices_table.php
 * Modified : mar., 13 févr. 2024 14:34
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MrWolfGb\Traccar\Support\TraccarMigration;

return new class extends TraccarMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!$this->shouldRun()) {
            return;
        }
        Schema::dropIfExists('traccar_devices');
        Schema::create('traccar_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uniqueId');
            $table->string('status')->nullable();
            $table->boolean('disabled')->nullable();
            $table->timestamp('lastUpdate')->nullable();
            $table->integer('positionId')->nullable();
            $table->integer('groupId')->nullable();
            $table->string('phone')->nullable();
            $table->string('model')->nullable();
            $table->string('contact')->nullable();
            $table->string('category')->nullable();
            $table->json('attribs')->nullable();
            $table->json('geofenceIds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('traccar_devices');
    }
};
