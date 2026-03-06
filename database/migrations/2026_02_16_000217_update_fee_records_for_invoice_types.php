<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_records', function (Blueprint $table) {
            $table->dropForeign(['fee_definition_id']);
            $table->dropUnique('fee_records_unique');
            $table->dropColumn('fee_definition_id');
            $table->unique(['invoice_type_id', 'student_id', 'academic_year_id'], 'fee_records_invoice_unique');
        });
    }

    public function down(): void
    {
        Schema::table('fee_records', function (Blueprint $table) {
            $table->dropUnique('fee_records_invoice_unique');
            $table->foreignId('fee_definition_id')->constrained('fee_definitions')->cascadeOnDelete();
        });

        Schema::table('fee_records', function (Blueprint $table) {
            $table->unique(['fee_definition_id', 'student_id', 'academic_year_id'], 'fee_records_unique');
        });
    }
};
