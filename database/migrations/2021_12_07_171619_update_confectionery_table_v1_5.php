<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConfectioneryTableV15 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('component', function(Blueprint $table){
            $table->boolean('isActive')->default(true);
        });

        Schema::table('users', function(Blueprint $table){
            $table->date('status_updated_at')->nullable();
        });

        Schema::table('user_status', function(Blueprint $table){
            $table->integer('will_update_in_months')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('component', function(Blueprint $table){
            $table->dropColumn('isActive');
        });

        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('status_updated_at');
        });

        Schema::table('user_status', function(Blueprint $table){
            $table->dropColumn('will_update_in_months');
        });
    }
}
