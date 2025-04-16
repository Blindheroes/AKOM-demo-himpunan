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
        Schema::create('lpjs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('template_id');
            $table->json('content');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'published'])->default('draft');
            $table->string('document_path')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approval_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')->references('id')->on('events')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('template_id')->references('id')->on('lpj_templates')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('approved_by')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpjs');
    }
};
