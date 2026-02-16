<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_definitions', function (Blueprint $table) {
            $table->foreignId('invoice_type_id')->nullable()->after('method')
                ->constrained('invoice_types')->nullOnDelete();
        });

        Schema::table('fee_records', function (Blueprint $table) {
            $table->foreignId('invoice_type_id')->nullable()->after('fee_definition_id')
                ->constrained('invoice_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fee_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_type_id');
        });

        Schema::table('fee_definitions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_type_id');
        });
    }
};
