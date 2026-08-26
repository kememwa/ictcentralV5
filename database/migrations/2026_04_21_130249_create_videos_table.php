<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['who-we-are', 'our-products', 'departmental-induction', 'knowledgebase']);
            $table->string('department')->nullable();
            $table->enum('video_type', ['upload', 'youtube']);
            
            // For uploaded videos
            $table->string('video_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            
            // For YouTube videos
            $table->string('youtube_id')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
};