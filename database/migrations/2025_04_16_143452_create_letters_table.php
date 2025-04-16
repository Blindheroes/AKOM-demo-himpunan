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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('template_id');
            $table->string('letter_number')->nullable();
            $table->date('date');
            $table->string('regarding');
            $table->string('recipient');
            $table->string('recipient_position')->nullable();
            $table->string('recipient_institution')->nullable();
            $table->longText('content');
            $table->text('attachment')->nullable();
            $table->enum('status', ['draft', 'pending', 'signed', 'sent', 'archived'])->default('draft');
            $table->string('document_path')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('signed_by')->nullable();
            $table->timestamp('signing_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('template_id')->references('id')->on('letter_templates')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('signed_by')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
