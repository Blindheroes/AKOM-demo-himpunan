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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['income', 'expense']);
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('lpj_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')->references('id')->on('events')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('lpj_id')->references('id')->on('lpjs')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')
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
        Schema::dropIfExists('financial_transactions');
    }
};
