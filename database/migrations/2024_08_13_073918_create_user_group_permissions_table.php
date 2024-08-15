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
        Schema::create('user_group_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_group_id');
            $table->foreignId('admin_menu_id');
            $table->boolean('index');
            $table->boolean('create');
            $table->boolean('edit');
            $table->boolean('destroy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_group_permissions');
    }
};
