<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->string('attendance_status')->default('hadir');
            $table->dateTime('check_in_at')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('distance_from_location', 10, 2)->nullable();
            $table->string('location_status')->default('perlu_verifikasi');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['athlete_id', 'training_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
