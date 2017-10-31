<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePocketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pockets', function (Blueprint $table) {
            $table->increments('id');

            $table->float('balance');
            $table->string('title');
            $table->text('description');

            $table->tinyInteger('has_notice')->default(0);

            $table->timestamps();
        });
        Schema::create('pocket_user', function (Blueprint $table) {
            $table->increments('id');

            // User
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Pocket
            $table->unsignedInteger('pocket_id');
            $table->foreign('pocket_id')
                ->references('id')
                ->on('pockets')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->tinyInteger('is_owner')->default(0);
            $table->unique(['user_id', 'pocket_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pockets');
    }
}
