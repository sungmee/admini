<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32)->default('post');
            $table->string('slug', 128)->unique();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        foreach (config('admini.languages') as $item) {
            $t = $item['language'] . 's';

            Schema::create($t, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id')->unique();
                $table->string('title', 100);
                $table->string('subtitle', 512)->nullable();
                $table->longText('pc');
                $table->longText('mobile')->nullable();
                $table->string('addition', 512)->nullable();

                $table->foreign('post_id')
                      ->references('id')->on('posts')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('posts');

        foreach (config('admini.languages') as $item) {
            $t = $item['language'] . 's';
            Schema::dropIfExists($t);
        }
    }
};
