<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConfectioneryTablesV21 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function(Blueprint $table){
            $table->dropColumn('review_photo');
            $table->dropColumn('review_text');
        });

        Schema::table('order-product', function(Blueprint $table){
            $table->text('review_text')->nullable();
            $table->string('review_photo')->nullable();
            $table->text('comment_text')->nullable();
            $table->string('comment_photo')->nullable();
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
            $table->text('review_text')->nullable();
            $table->string('review_photo')->nullable();
        });

        Schema::table('order-product', function(Blueprint $table){
            $table->dropColumn('review_photo');
            $table->dropColumn('review_text');
            $table->dropColumn('comment_photo');
            $table->dropColumn('comment_text');
        });
    }
}
