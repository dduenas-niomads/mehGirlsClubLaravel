<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopUserCupon extends Model
{
    protected $connection = 'mysql';
    const TABLE_NAME = 'shop_users_cupons';
    const STATE_ACTIVE = true;
    const STATE_INACTIVE = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //Table Rows
        'id','shop_users_id','type','points','name','description','code',
        //Audit 
        'flag_active','created_at','updated_at','deleted_at',
    ];
    /**
     * Casting of attributes
     *
     * @var array
     */
    protected $casts = [
    ];

    public function shopUser()
    {
        return $this->belongsTo('App\Models\ShopUser', 'shop_users_id');
    }
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