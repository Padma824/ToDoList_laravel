<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->string('title')->after('group_id');
        $table->string('completed')->default(false);
            
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {

            
            //
        });
    }
};
