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
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image_path')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'published', 'canceled', 'completed'])->default('draft');
            $table->integer('max_participants')->nullable();
            $table->dateTime('registration_deadline')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->decimal('budget', 15, 2)->nullable();
            $table->json('approval_workflow_status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organizer_id')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('events');
    }
};
