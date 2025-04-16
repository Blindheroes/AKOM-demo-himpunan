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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'archived'])->default('draft');
            $table->unsignedInteger('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('author_id')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
