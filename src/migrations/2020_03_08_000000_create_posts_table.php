<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['post', 'page', 'new', 'notice', 'file', 'menu', 'homepage'])->default('post');
            $table->string('slug', 128)->unique();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        foreach (config('admini.languages') as $item) {
            $t = $item['language'] . 's';

            Schema::create($t, function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('post_id')->unique();
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');

        foreach (config('admini.languages') as $item) {
            $t = $item['language'] . 's';
            Schema::dropIfExists($t);
        }
    }
}
