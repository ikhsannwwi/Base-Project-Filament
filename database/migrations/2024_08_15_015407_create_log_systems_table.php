<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('ip_address');
            $table->string('device');
            $table->string('browser_name');
            $table->string('browser_version');
            $table->string('module');
            $table->string('action')->nullable();
            $table->foreignId('data_id');
            $table->longtext('data');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_systems');
    }
};
