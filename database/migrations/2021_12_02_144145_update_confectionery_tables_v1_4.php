<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConfectioneryTablesV14 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function(Blueprint $table){
            $table->double('paidByPoints')->nullable();
        });

        Schema::create('source', function(Blueprint $table){
            $table->id();
            $table->string('source');
            $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table){
           $table->dropColumn('from');
           $table->foreignId('id_source')->nullable()->constrained('source')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function(Blueprint $table){
            $table->dropColumn('paidByPoints');
        });

        Schema::table('users', function(Blueprint $table){
            $table->string('from')->nullable();
            $table->dropForeign('users_id_source_foreign');
            $table->dropColumn('id_source');
        });

        Schema::dropIfExists('source');
    }
}
