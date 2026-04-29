<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('curhats', function (Blueprint $table) {
            $table->string('emoji')->nullable()->after('mood');
        });
    }

    public function down(): void
    {
        Schema::table('curhats', function (Blueprint $table) {
            $table->dropColumn('emoji');
        });
    }
};
