<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('image_path'); 
            $table->string('link_url')->nullable(); 
            $table->enum('position', [
                'top_header',      
                'home_middle',     
                'article_top',     
                'article_middle',  
                'article_bottom',  
                'sidebar'          
            ]);
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};