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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('venue');
            $table->string('city')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('capacity')->nullable();
            $table->decimal('ticket_price', 10, 2)->nullable();
            $table->string('status')->default('draft'); // draft, published, cancelled
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes

            // Add indexes for better performance
            $table->index(['status', 'start_date']);
            $table->index(['city', 'status']);
            $table->index(['organizer_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index('start_date');
            $table->index('status');
            $table->fullText(['title', 'description', 'venue']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
