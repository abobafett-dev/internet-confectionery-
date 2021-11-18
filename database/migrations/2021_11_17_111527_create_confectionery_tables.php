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
            $table->string('gender',10)->after('password')->nullable();
            $table->timestamp('birthday')->after('gender')->nullable();
            $table->string('from')->after('birthday')->nullable();
            $table->string('phone')->after('from');
            $table->string('avatar')->after('phone')->nullable();
            $table->double('bonus')->after('avatar')->default(0);
            $table->string('status')->after('bonus')->default(0);
        });

        Schema::create('order_status', function(Blueprint $table){
            $table->id();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('order', function(Blueprint $table){
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_status')->constrained('order_status')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamp('cooked_at');
            $table->string('address');
            $table->text('review_text')->nullable();
            $table->string('review_photo')->nullable();
            $table->timestamps();
        });

        Schema::create('product_type', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('product', function(Blueprint $table){
            $table->id();
            $table->foreignId('id_product_type')->constrained('product_type')->onUpdate('cascade')->onDelete('restrict');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('weight');
            $table->string('photo');
            $table->timestamps();
        });

        Schema::create('order-product', function(Blueprint $table){
            $table->foreignId('id_order')->constrained('order')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_product')->constrained('product')->onUpdate('cascade')->onDelete('restrict');
            $table->primary(['id_order','id_product']);
            $table->integer('count')->default(1);
            $table->timestamps();
        });

        Schema::create('component_type', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('component', function(Blueprint $table){
            $table->id();
            $table->foreignId('id_component_type')->constrained('component_type')->onUpdate('cascade')->onDelete('restrict');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('coefficient')->default(1);
            $table->double('price');
            $table->string('photo');
            $table->timestamps();
        });

        Schema::create('product-component', function(Blueprint $table){
            $table->foreignId('id_product')->constrained('product')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_component')->constrained('component')->onUpdate('cascade')->onDelete('restrict');
            $table->primary(['id_product','id_component']);
            $table->double('weight');
            $table->timestamps();
        });

        Schema::create('product_type-component', function(Blueprint $table){
            $table->foreignId('id_product_type')->constrained('product_type')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_component')->constrained('component')->onUpdate('cascade')->onDelete('restrict');
            $table->primary(['id_product_type','id_component']);
            $table->timestamps();
        });

        Schema::create('ingredient', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ingredient-component', function(Blueprint $table){
            $table->foreignId('id_ingredient')->constrained('ingredient')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_component')->constrained('component')->onUpdate('cascade')->onDelete('restrict');
            $table->primary(['id_ingredient','id_component']);
            $table->double('weight');
            $table->timestamps();
        });

        Schema::create('purchase', function(Blueprint $table){
            $table->id();
            $table->string('seller');
            $table->timestamp('purchased_at');
            $table->timestamps();
        });

        Schema::create('ingredient-purchase', function(Blueprint $table){
            $table->foreignId('id_ingredient')->constrained('ingredient')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_purchase')->constrained('purchase')->onUpdate('cascade')->onDelete('restrict');
            $table->primary(['id_ingredient','id_purchase']);
            $table->double('weight');
            $table->double('price');
            $table->timestamps();
        });

        Schema::create('schedule_standard', function(Blueprint $table){
            $table->id();
            $table->string('weekday',20);
            $table->time('start');
            $table->time('end');
            $table->integer('orders_count');
            $table->timestamp('purchased_at');
            $table->timestamps();
        });

        Schema::create('schedule_update', function(Blueprint $table){
            $table->id();
            $table->time('start');
            $table->time('end');
            $table->boolean('access')->default(0);
            $table->integer('orders_count_update')->default(0);
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
        Schema::table('order', function(Blueprint $table){
            $table->dropForeign('order_id_status_foreign');
            $table->dropForeign('order_id_user_foreign');
        });

        Schema::table('order-product', function(Blueprint $table){
            $table->dropForeign('order_product_id_order_foreign');
            $table->dropForeign('order_product_id_product_foreign');
        });

        Schema::table('product', function(Blueprint $table){
            $table->dropForeign('product_id_product_type_foreign');
        });

        Schema::table('product-component', function(Blueprint $table){
            $table->dropForeign('product_component_id_component_foreign');
            $table->dropForeign('product_component_id_product_foreign');
        });

        Schema::table('product_type-component', function(Blueprint $table){
            $table->dropForeign('product_type_component_id_component_foreign');
            $table->dropForeign('product_type_component_id_product_type_foreign');
        });

        Schema::table('component', function(Blueprint $table){
            $table->dropForeign('component_id_component_type_foreign');
        });

        Schema::table('ingredient-component', function(Blueprint $table){
            $table->dropForeign('ingredient_component_id_component_foreign');
            $table->dropForeign('ingredient_component_id_ingredient_foreign');
        });

        Schema::table('ingredient-purchase', function(Blueprint $table){
            $table->dropForeign('ingredient_purchase_id_ingredient_foreign');
            $table->dropForeign('ingredient_purchase_id_purchase_foreign');
        });

        Schema::dropIfExists('order_status');

        Schema::dropIfExists('order');

        Schema::dropIfExists('product_type');

        Schema::dropIfExists('product');

        Schema::dropIfExists('order-product');

        Schema::dropIfExists('component_type');

        Schema::dropIfExists('component');

        Schema::dropIfExists('product-component');

        Schema::dropIfExists('product_type-component');

        Schema::dropIfExists('ingredient');

        Schema::dropIfExists('purchase');

        Schema::dropIfExists('ingredient-component');

        Schema::dropIfExists('ingredient-purchase');

        Schema::dropIfExists('schedule_standard');

        Schema::dropIfExists('schedule_update');


    }
}
