<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    protected $connection = 'mysql';
    const TABLE_NAME = 'shop_users';
    const STATE_ACTIVE = true;
    const STATE_INACTIVE = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //Table Rows
        'id',
        'shop_id',
        'email',
        'document_number',
        'birthday',
        'loyalty_points',
        'loyalty_points_available',
        'loyalty_points_for_sales',
        'loyalty_points_for_extras',
        'loyalty_used_points',
        'loyalty_level',
        'accepts_marketing',
        'first_name',
        'last_name',
        'orders_count',
        'state',
        'total_spent',
        'last_order_id',
        'note',
        'verified_email',
        'multipass_identifier',
        'tax_exempt',
        'phone',
        'tags',
        'last_order_name',
        'currency',
        'addresses',
        'default_address',
        'accepts_marketing_updated_at',
        'marketing_opt_in_level',
        'tax_exemptions',
        'admin_graphql_api_id',
        //Audit 
        'flag_active','created_at','updated_at','deleted_at',
    ];
    /**
     * Casting of attributes
     *
     * @var array
     */
    protected $casts = [
        'addresses' => 'array',
        'default_address' => 'array',
        'tax_exemptions' => 'array'
    ];
    public function getFillable() {
        # code...
        return $this->fillable;
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = self::TABLE_NAME;
}