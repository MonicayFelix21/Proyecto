<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpotifyColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('spotify_token')->nullable();
            $table->string('spotify_refresh_token')->nullable();
            $table->timestamp('spotify_token_expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'spotify_token',
                'spotify_refresh_token',
                'spotify_token_expires_at',
            ]);
        });
    }
}
