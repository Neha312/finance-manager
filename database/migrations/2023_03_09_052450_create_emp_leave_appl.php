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
        Schema::create('emp_leave_appl', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->char('financial_year_id', 36);
            $table->char('user_id', 36);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('num_days');
            $table->string('description', 551)->nullable();
            $table->enum('leave_status', ['IR', 'IM', 'IH', 'A', 'R'])->comment('IR: in_reporting_to,IM: in_managed_by,IH: in_hr,A: Approved, R: Rejected');
            $table->boolean('is_planned')->default(true);
            $table->char('pending_with', 36)->nullable();
            $table->char('approval_by', 36)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();


            $table->foreign('financial_year_id')->references('id')->on('financial_years')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approval_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pending_with')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_leave_appl');
    }
};
