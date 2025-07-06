<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get admin role ID
        $adminRole = DB::table('roles')->where('slug', 'admin')->first();

        if ($adminRole) {
            // Delete users with admin role
            DB::table('users')->where('role_id', $adminRole->id)->delete();

            // Delete admin role
            DB::table('roles')->where('slug', 'admin')->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create admin role
        DB::table('roles')->insert([
            'name' => 'Administrator',
            'slug' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Note: We don't restore admin users as their data would be lost
    }
};
