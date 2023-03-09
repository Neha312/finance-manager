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
        Schema::create('emp_leave_appl_attachments', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->char('leave_appl_id', 36);
            $table->string('name', 128)->nullable();
            $table->string('url', 255)->nullable();
            $table->timestamps();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();

            $table->foreign('leave_appl_id')->references('id')->on('emp_leave_appl')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_leave_appl_attachments');
    }
};
