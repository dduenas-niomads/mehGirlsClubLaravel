<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_users', function (Blueprint $table) {
            $table->id();
            $table->biginteger('shop_id');
            $table->string('email')->nullable();
            $table->string('document_number')->nullable();
            $table->string('birthday')->nullable();
            $table->integer('loyalty_points')->default(0);
            $table->integer('loyalty_points_available')->default(0);
            $table->integer('loyalty_points_for_sales')->default(0);
            $table->integer('loyalty_points_for_extras')->default(0);
            $table->integer('loyalty_used_points')->default(0);
            $table->integer('loyalty_level')->default(0);
            $table->string('loyalty_level_name')->default(0);
            $table->boolean('accepts_marketing')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('orders_count')->nullable();
            $table->string('state')->nullable();
            $table->string('total_spent')->nullable();
            $table->string('last_order_id')->nullable();
            $table->string('note')->nullable();
            $table->boolean('verified_email')->nullable();
            $table->string('multipass_identifier')->nullable();
            $table->boolean('tax_exempt')->nullable();
            $table->string('phone')->nullable();
            $table->string('tags')->nullable();
            $table->string('last_order_name')->nullable();
            $table->string('currency')->nullable();
            $table->json('addresses')->nullable();
            $table->json('default_address')->nullable();
            $table->timestamp('accepts_marketing_updated_at')->nullable();
            $table->string('marketing_opt_in_level')->nullable();
            $table->json('tax_exemptions')->nullable();
            $table->string('admin_graphql_api_id')->nullable();
            // auditory
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
        Schema::dropIfExists('shop_users');
    }
}
