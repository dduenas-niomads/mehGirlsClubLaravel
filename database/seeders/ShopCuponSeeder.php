<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopCuponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shop_cupons')->insert([
            "type" => 1,
            "points" => 100,
            "price_rule_id" => "930076655663",
            "name" => "10% OFF",
            "description" => "NO APLICA CON OTROS DSCTOS Y/O PROMOCIONES. SÓLO INGRESA EL CÓDIGO AL HACER CHECKOUT"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 1,
            "points" => 200,
            "price_rule_id" => "930076753967",
            "name" => "15% OFF",
            "description" => "NO APLICA CON OTROS DSCTOS Y/O PROMOCIONES. SÓLO INGRESA EL CÓDIGO AL HACER CHECKOUT"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 1,
            "points" => 450,
            "price_rule_id" => "930076819503",
            "name" => "25% OFF",
            "description" => "NO APLICA CON OTROS DSCTOS Y/O PROMOCIONES. SÓLO INGRESA EL CÓDIGO AL HACER CHECKOUT"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 1,
            "points" => 1500,
            "price_rule_id" => "930076917807",
            "name" => "50% OFF",
            "description" => "NO APLICA CON OTROS DSCTOS Y/O PROMOCIONES. SÓLO INGRESA EL CÓDIGO AL HACER CHECKOUT"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 3,
            "points" => 2500,
            "price_rule_id" => null,
            "name" => "MEH BASIC SORPRESA",
            "description" => "RECIBE UN MEH BASIC GRATIS EN TU PROXIMA COMPRA"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 3,
            "points" => 500,
            "price_rule_id" => null,
            "name" => "1 DENIM",
            "description" => "RECIBE TU DENIM FAVORITO EN TU PROXIMA COMPRA"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 3,
            "points" => 500,
            "price_rule_id" => null,
            "name" => "1 PAR DE ZAPATOS",
            "description" => "RECIBE TU PAR DE ZAPATOS FAVORITOS EN TU PROXIMA COMPRA"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 4,
            "points" => 100,
            "price_rule_id" => "930077081647",
            "name" => "1 ENVIO ESTANDAR GRATIS",
            "description" => "NO APLICA CON OTROS DSCTOS Y/O PROMOCIONES. SÓLO INGRESA EL CÓDIGO AL HACER CHECKOUT"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 2,
            "points" => 250,
            "price_rule_id" => "930077343791",
            "name" => "S/50 DE DSCTO",
            "description" => "MIN DE COMPRA S/150.00"
        ]);
        DB::table('shop_cupons')->insert([
            "type" => 2,
            "points" => 350,
            "price_rule_id" => "930077409327",
            "name" => "S/100 DE DSCTO",
            "description" => "MIN DE COMPRA S/300.00"
        ]);
    }
}
