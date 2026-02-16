<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['class_id', 'section_id']);
        });

        if (Schema::hasColumn('sections', 'class_id')) {
            $rows = DB::table('sections')
                ->whereNotNull('class_id')
                ->get(['id', 'class_id'])
                ->map(function ($row) {
                    return [
                        'class_id' => $row->class_id,
                        'section_id' => $row->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })
                ->toArray();

            if (!empty($rows)) {
                DB::table('class_section')->insertOrIgnore($rows);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('class_section');
    }
};
