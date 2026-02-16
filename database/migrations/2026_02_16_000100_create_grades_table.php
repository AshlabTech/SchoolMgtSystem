<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_level_id')->nullable()->constrained('class_levels')->nullOnDelete();
            $table->string('name', 40);
            $table->unsignedTinyInteger('mark_from');
            $table->unsignedTinyInteger('mark_to');
            $table->string('remark', 80)->nullable();
            $table->timestamps();
            $table->unique(['class_level_id', 'name', 'remark']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
