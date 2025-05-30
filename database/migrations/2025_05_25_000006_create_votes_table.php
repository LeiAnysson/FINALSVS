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
        Schema::create('votes', function (Blueprint $table) {
            $table->bigIncrements('vote_id');
            $table->unsignedBigInteger('voter_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('election_id');
            $table->timestamp('voted_at')->useCurrent();
            $table->timestamps();
            
            //FKs
            $table->foreign('voter_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('candidate_id')->references('candidate_id')->on('candidates')->onDelete('cascade');
            $table->foreign('election_id')->references('election_id')->on('elections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
