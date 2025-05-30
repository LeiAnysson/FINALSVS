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
        Schema::create('otp', function (Blueprint $table) {
            $table->bigIncrements('otp_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('election_id');
            $table->string('code');
            $table->boolean('isUsed')->default(false);
            $table->timestamp('expired_at');
            $table->timestamps();
            
            //FKs
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('election_id')->references('election_id')->on('elections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp');
    }
};
