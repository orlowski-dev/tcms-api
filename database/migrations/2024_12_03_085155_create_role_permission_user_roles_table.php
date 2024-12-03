<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permission_user_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->primary()->index();
            $table->foreignId('role_id')->primary()->index();

            $table->foreign('permission_id')->on('role_permissions')->references('id');
            $table->foreign('role_id')->on('user_roles')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission_user_role');
    }
};
