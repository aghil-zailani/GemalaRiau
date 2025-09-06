// database/migrations/xxxx_xx_xx_xxxxxx_create_articles_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            // Data Inti
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Penulis

            // Data untuk Foto/Gambar
            $table->string('image')->nullable();
            $table->string('image_caption')->nullable();

            // Data Tambahan
            $table->text('excerpt');
            $table->string('status')->default('draft'); // 'draft' atau 'published'
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_breaking')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};