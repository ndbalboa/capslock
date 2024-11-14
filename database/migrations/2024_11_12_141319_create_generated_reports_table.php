<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratedReportsTable extends Migration
{
    public function up(): void
    {
        Schema::create('generated_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // Type of report (e.g., "office order", "travel order")
            $table->string('file_path'); // Path to the generated PDF file
            $table->date('start_date')->nullable(); // Start date of the report range
            $table->date('end_date')->nullable(); // End date of the report range
            $table->string('employee')->nullable(); // Employee filter applied, if any
            $table->timestamps(); // Automatically includes created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_reports');
    }
}
