<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCuponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_cupons', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1);
            $table->integer('points')->default(0);
            $table->string('price_rule_id')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('url_image')->nullable();
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
        Schema::dropIfExists('shop_cupons');
    }
}
