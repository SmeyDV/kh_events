<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropColumn('ticket_price');
    });
  }

  public function down(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->decimal('ticket_price', 10, 2)->nullable();
    });
  }
};
