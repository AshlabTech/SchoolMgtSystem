<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('label')->nullable()->after('key');
            $table->string('type')->default('text')->after('group');
            $table->json('options')->nullable()->after('type');
            $table->string('options_source')->nullable()->after('options');
            $table->string('options_label')->nullable()->after('options_source');
            $table->string('options_value')->nullable()->after('options_label');
            $table->text('description')->nullable()->after('options_value');
            $table->boolean('is_locked')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'label',
                'type',
                'options',
                'options_source',
                'options_label',
                'options_value',
                'description',
                'is_locked',
            ]);
        });
    }
};
