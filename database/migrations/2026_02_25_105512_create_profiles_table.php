<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('bio')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('location')->nullable();
                $table->string('skills')->nullable();
                $table->string('image')->nullable();
                $table->integer('onboarding_step')->default(1);
                $table->boolean('completed')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};