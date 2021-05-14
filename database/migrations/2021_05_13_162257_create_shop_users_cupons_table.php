<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopUsersCuponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_users_cupons', function (Blueprint $table) {
            $table->id();
            $table->biginteger('shop_users_id')->unsigned(); 
            $table->index('shop_users_id');
            $table->foreign('shop_users_id')->references('id')->on('shop_users')->onDelete('cascade');
            $table->tinyInteger('type')->default(1);
            $table->integer('points')->default(0);
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('code')->nullable();
            $table->tinyInteger('flag_active')->default(1);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_users_cupons');
    }
}
