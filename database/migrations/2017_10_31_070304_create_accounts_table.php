<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');

            $table->float('balance')->default(0);
            $table->string('title')->default('');


            $table->timestamps();
        });

        Schema::create('account_user', function (Blueprint $table) {
            $table->increments('id');

            // User
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Account
            $table->unsignedInteger('account_id');
            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->tinyInteger('is_owner')->default(0);

            $table->unique(['user_id', 'account_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('account_user');
    }
}
