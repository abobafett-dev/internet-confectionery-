<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConfectioneryTablesV13 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order-product', function(Blueprint $table){
            $table->double('weight')->nullable();
        });

        Schema::table('product-component', function(Blueprint $table){
            $table->dropColumn('weight');
        });

        Schema::table('product_type', function(Blueprint $table){
            $table->double('weight_min')->nullable();
            $table->double('weight_initial')->nullable();
            $table->double('weight_max')->nullable();
        });

        Schema::table('product', function(Blueprint $table){
            $table->double('price')->nullable();
            $table->double('bonus_coefficient')->nullable();
            $table->boolean('isActive')->nullable();
            $table->dropColumn('weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order-product', function(Blueprint $table){
            $table->dropColumn('weight');
        });

        Schema::table('product-component', function(Blueprint $table){
            $table->double('weight')->nullable();
        });

        Schema::table('product_type', function(Blueprint $table){
            $table->dropColumn('weight_min');
            $table->dropColumn('weight_initial');
            $table->dropColumn('weight_max');
        });

        Schema::table('product', function(Blueprint $table){
            $table->dropColumn('price');
            $table->dropColumn('bonus_coefficient');
            $table->dropColumn('isActive');
            $table->double('weight')->nullable();
        });
    }
}
