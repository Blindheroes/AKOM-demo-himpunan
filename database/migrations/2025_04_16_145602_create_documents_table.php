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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->enum('category', ['report', 'proposal', 'minutes', 'regulation', 'certificate', 'other'])->default('other');
            $table->enum('visibility', ['public', 'members', 'executives', 'admin'])->default('members');
            $table->enum('status', ['draft', 'pending', 'approved', 'published', 'archived'])->default('draft');
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approval_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')
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
        Schema::dropIfExists('documents');
    }
};
