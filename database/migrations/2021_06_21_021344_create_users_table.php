<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('locale')->nullable();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover')->nullable();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->boolean('show_online')->default(1);
            $table->timestamp('marked_read_at')->nullable()->index();
            $table->json('notification_preferences')->nullable();
            $table->timestamp('notifications_read_at')->nullable();
            $table->timestamp('suspend_until')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
