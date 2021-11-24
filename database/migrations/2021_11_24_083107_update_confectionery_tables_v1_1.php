<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConfectioneryTablesV11 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_status', function(Blueprint $table){
           $table->id();
           $table->string('name');
           $table->double('percent')->nullable();
           $table->double('toAchieveThat')->nullable();
           $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table){
            $table->foreignId('id_user_status')->default(1)->constrained('user_status')->onUpdate('cascade')->onDelete('restrict');
            $table->string('phone')->nullable(false)->change();
            $table->dropColumn('status');
        });

        Schema::create('schedule_interval', function (Blueprint $table){
            $table->id();
            $table->time('start');
            $table->time('end');
            $table->timestamps();
        });

        Schema::table('order', function(Blueprint $table){
            $table->foreignId('id_schedule_standard')->constrained('schedule_standard')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_schedule_interval')->constrained('schedule_interval')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::table('schedule_update', function (Blueprint $table){
            $table->foreignId('id_schedule_interval')->constrained('schedule_interval')->onUpdate('cascade')->onDelete('restrict');
            $table->dropColumn('start');
            $table->dropColumn('end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign('users_id_user_status_foreign');
            $table->string('phone')->nullable(true)->change();
            $table->string('status')->default(1);
        });

        Schema::table('order', function(Blueprint $table){
            $table->dropForeign('order_id_schedule_standard_foreign');
            $table->dropForeign('order_id_schedule_interval_foreign');
        });

        Schema::dropIfExists('user_status');

        Schema::table('schedule_update', function (Blueprint $table){
            $table->dropForeign('schedule_update_id_schedule_interval_foreign');
            $table->time('start');
            $table->time('end');
        });

        Schema::dropIfExists('schedule_interval');
    }
}
