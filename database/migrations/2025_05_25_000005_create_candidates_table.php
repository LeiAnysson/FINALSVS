<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->bigIncrements('candidate_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('position_id');
            $table->text('description')->nullable();
            $table->timestamps();
            
            //FKs
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('election_id')->references('election_id')->on('elections')->onDelete('cascade');
            $table->foreign('position_id')->references('position_id')->on('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
