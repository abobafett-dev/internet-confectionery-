<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfectioneryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender',10)->after('password');
            $table->timestamp('birthday')->after('gender');
            $table->string('from')->after('birthday');
            $table->string('phone')->after('from');
            $table->string('avatar')->after('phone');
            $table->double('bonus')->after('avatar');
            $table->string('status')->after('bonus');
        });

        Schema::table('order_status', function(Blueprint $table){
            $table->id();
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('order', function(Blueprint $table){
            $table->id();
            $table->timestamp('cooked_at');
            $table->string('address');
            $table->text('review_text');
            $table->string('review_photo');
            $table->timestamps();
        });

        Schema::table('order-product', function(Blueprint $table){

            $table->integer('count');
            $table->timestamps();
        });

        Schema::table('product', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->double('weight');
            $table->string('photo');
            $table->timestamps();
        });

        Schema::table('product_type', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('product-component', function(Blueprint $table){

            $table->double('weight');
            $table->timestamps();
        });

        Schema::table('product_type-component', function(Blueprint $table){

            $table->timestamps();
        });

        Schema::table('component', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->double('coefficient');
            $table->double('price');
            $table->string('photo');
            $table->timestamps();
        });

        Schema::table('component_type', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('ingredient-component', function(Blueprint $table){

            $table->double('weight');
            $table->timestamps();
        });

        Schema::table('ingredient', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::table('ingredient-purchase', function(Blueprint $table){

            $table->double('weight');
            $table->double('price');
            $table->timestamps();
        });

        Schema::table('purchase', function(Blueprint $table){
            $table->id();
            $table->string('seller');
            $table->timestamp('purchased_at');
            $table->timestamps();
        });

        Schema::table('schedule_standard', function(Blueprint $table){
            $table->id();
            $table->string('weekday',20);
            $table->time('start');
            $table->time('end');
            $table->integer('orders_count');
            $table->timestamp('purchased_at');
            $table->timestamps();
        });

        Schema::table('schedule_update', function(Blueprint $table){
            $table->id();
            $table->time('start');
            $table->time('end');
            $table->boolean('access');
            $table->integer('orders_count_update');
            $table->timestamp('purchased_at');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_status');

        Schema::dropIfExists('order');

        Schema::dropIfExists('order-product');

        Schema::dropIfExists('product');

        Schema::dropIfExists('product_type');

        Schema::dropIfExists('product-component');

        Schema::dropIfExists('product_type-component');

        Schema::dropIfExists('component');

        Schema::dropIfExists('component_type');

        Schema::dropIfExists('ingredient-component');

        Schema::dropIfExists('ingredient');

        Schema::dropIfExists('ingredient-purchase');

        Schema::dropIfExists('purchase');

        Schema::dropIfExists('schedule_standard');

        Schema::dropIfExists('schedule_update');


    }
}
