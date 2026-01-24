<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('content')->nullable();
            $table->enum('category', ['city_event', 'facility_news', 'promotion', 'announcement', 'holiday']);
            $table->string('image_path')->nullable();
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();
            $table->string('location')->nullable();
            $table->string('organizer')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->json('tags')->nullable();
            $table->timestamp('published_at')->nullable();
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            $table->index(['category', 'is_published']);
            $table->index('event_date');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('events');
    }
};
