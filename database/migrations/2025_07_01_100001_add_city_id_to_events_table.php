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
    Schema::table('events', function (Blueprint $table) {
      $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
      $table->dropColumn('city');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropForeign(['city_id']);
      $table->dropColumn('city_id');
      $table->string('city')->nullable();
    });
  }
};
