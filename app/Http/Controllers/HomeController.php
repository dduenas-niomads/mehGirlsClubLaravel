<?php

namespace App\Http\Controllers;

use App\Models\ShopUser;
use App\Models\ShopUserCupon;
use App\Models\ShopCupon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private static function getShopifyClient()
    {
        $credential = new \Slince\Shopify\PrivateAppCredential(
            env('ECOMMERCE_API_KEY'),
            env('ECOMMERCE_PASSWORD'),
            env('ECOMMERCE_SHARED_SECRET'));
        $client = new \Slince\Shopify\Client(env('ECOMMERCE_STORE') . '.myshopify.com',
            $credential, 
            [
                'meta_cache_dir' => env('META_CACHE_DIR')
                // CLOUD '/app/storage/app/public/cache/tmp'
                // LOCAL './tmp'
            ]
        );
        return $client;
    }
    /**
     * Display a listing of the cars
     *
     * @param  \App\Models\Car  $model
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if (isset($params['userId']) && (int)$params['userId'] !== 0) {
            // obtener apiclient
            $shopifyClient = self::getShopifyClient();
            // cargar usuario
            try {
                $customer = $shopifyClient->getCustomerManager()->find((int)$params['userId']);
                // buscar registro
                $shopUser = ShopUser::whereNull(ShopUser::TABLE_NAME . '.deleted_at')
                                ->where(ShopUser::TABLE_NAME . '.shop_id', $customer->getId())
                                ->first();
                if (is_null($shopUser)) {
                    // crear nuevo usuario
                    $shopUser = new ShopUser();
                    $shopUser->shop_id = $customer->getId();
                    $shopUser->email = $customer->getEmail();
                    $shopUser->accepts_marketing = $customer->isAcceptsMarketing();
                    $shopUser->first_name = $customer->getFirstName();
                    $shopUser->last_name = $customer->getLastName();
                    $shopUser->orders_count = $customer->getOrdersCount();
                    $shopUser->state = $customer->getState();
                    $shopUser->total_spent = $customer->getTotalSpent();
                    $shopUser->last_order_id = $customer->getLastOrderId();
                    $shopUser->note = $customer->getNote();
                    $shopUser->verified_email = $customer->isVerifiedEmail();
                    $shopUser->multipass_identifier = $customer->getMultipassIdentifier();
                    $shopUser->tax_exempt = $customer->isTaxExempt();
                    $shopUser->phone = $customer->getPhone();
                    $shopUser->tags = $customer->getTags();
                    $shopUser->last_order_name = $customer->getLastOrderName();
                    $shopUser->currency = $customer->getCurrency();
                    $shopUser->addresses = $customer->getAddressesArray();
                    $shopUser->accepts_marketing_updated_at = $customer->getAcceptsMarketingUpdatedAt()->format('Y-m-d H:i:s');
                    $shopUser->marketing_opt_in_level = $customer->getMarketingOptInLevel();
                    $shopUser->tax_exemptions = $customer->getTaxExemptions();
                    $shopUser->admin_graphql_api_id = $customer->getAdminGraphqlApiId();
                    $shopUser->default_address = $customer->getDefaultAddressArray();
                    $shopUser->created_at = $customer->getCreatedAt()->format('Y-m-d H:i:s');
                    $shopUser->updated_at = $customer->getUpdatedAt()->format('Y-m-d H:i:s');
                    // agregar 50 puntos al registrarse
                    $shopUser->loyalty_points_for_extras = 50;
                    $shopUser->save();
                    // crear puntaje en historial
                    $shopUserCupon = new ShopUserCupon();
                    $shopUserCupon->shop_users_id = $shopUser->id;
                    $shopUserCupon->points = 50;
                    $shopUserCupon->code = "EXTPOINT_NEW_USER";
                    $shopUserCupon->name = 'EXTRA POINTS';
                    $shopUserCupon->description = '¡Recibe 50 puntos al formar parte del club!';
                    $shopUserCupon->created_at = date('Y-m-d H:i:s');
                    $shopUserCupon->save();
                } else {
                    // actualizar datos de usuario
                    $shopUser->shop_id = $customer->getId();
                    $shopUser->email = $customer->getEmail();
                    $shopUser->accepts_marketing = $customer->isAcceptsMarketing();
                    $shopUser->first_name = $customer->getFirstName();
                    $shopUser->last_name = $customer->getLastName();
                    $shopUser->orders_count = $customer->getOrdersCount();
                    $shopUser->state = $customer->getState();
                    $shopUser->total_spent = $customer->getTotalSpent();
                    $shopUser->last_order_id = $customer->getLastOrderId();
                    $shopUser->note = $customer->getNote();
                    $shopUser->verified_email = $customer->isVerifiedEmail();
                    $shopUser->multipass_identifier = $customer->getMultipassIdentifier();
                    $shopUser->tax_exempt = $customer->isTaxExempt();
                    // $shopUser->phone = $customer->getPhone();
                    $shopUser->tags = $customer->getTags();
                    $shopUser->last_order_name = $customer->getLastOrderName();
                    $shopUser->currency = $customer->getCurrency();
                    $shopUser->addresses = $customer->getAddressesArray();
                    $shopUser->accepts_marketing_updated_at = $customer->getAcceptsMarketingUpdatedAt()->format('Y-m-d H:i:s');
                    $shopUser->marketing_opt_in_level = $customer->getMarketingOptInLevel();
                    $shopUser->tax_exemptions = $customer->getTaxExemptions();
                    $shopUser->admin_graphql_api_id = $customer->getAdminGraphqlApiId();
                    // $shopUser->default_address = $customer->getDefaultAddressArray();
                    $shopUser->created_at = $customer->getCreatedAt()->format('Y-m-d H:i:s');
                    $shopUser->updated_at = $customer->getUpdatedAt()->format('Y-m-d H:i:s');
                    $shopUser->save();
                }

                // buscar órdenes
                $orders = $shopifyClient->getOrderManager()->findAll([
                    "status" => "any",
                    "email" => $customer->getEmail(),
                    "financial_status" => "paid",
                    "confirmed" => true
                ]);

                // calcular puntos y niveles
                $loyaltyPointsForSales = 0;
                $orders_ = [];
                foreach ($orders as $key => $value) {
                    $loyaltyPointsForSales = $loyaltyPointsForSales + $value->getTotalPrice();
                    if ($value->getTotalPrice() > 0) {
                        array_push($orders_, $value);
                    }
                }
                // guardar detalle de ventas en historial
                foreach ($orders_ as $key => $value) {
                    $shopUserCupon = ShopUserCupon::whereNull(ShopUserCupon::TABLE_NAME . '.deleted_at')
                                        ->where(ShopUserCupon::TABLE_NAME . '.code', $value->getOrderNumber())
                                        ->where(ShopUserCupon::TABLE_NAME . '.shop_users_id', $shopUser->id)
                                        ->first();
                    if (is_null($shopUserCupon)) {
                        $shopUserCupon = new ShopUserCupon();
                        $shopUserCupon->shop_users_id = $shopUser->id;
                        $shopUserCupon->points = ceil($value->getTotalPrice());
                        $shopUserCupon->code = $value->getOrderNumber();
                        $shopUserCupon->name = 'COMPRA';
                        $shopUserCupon->description = '1 sol = 1 Meh point';
                        $shopUserCupon->created_at = $value->getCreatedAt()->format('Y-m-d H:i:s');
                        $shopUserCupon->save();
                    }
                }
                $orders = $orders_;
                // actualizar puntaje loyalty
                // puntos: round/ceil
                $shopUser->loyalty_points_for_sales = ceil($loyaltyPointsForSales);
                $shopUser->loyalty_points = $shopUser->loyalty_points_for_sales + $shopUser->loyalty_points_for_extras;
                // niveles
                if ($shopUser->loyalty_points <= 750) {
                    # meh intern
                    $shopUser->loyalty_level = 1;
                    $shopUser->loyalty_level_name = "MEH INTERN";
                }
                if ($shopUser->loyalty_points > 750 && $shopUser->loyalty_points < 2000) {
                    # meh insider
                    $shopUser->loyalty_level = 2;
                    $shopUser->loyalty_level_name = "MEH INSIDER";
                }
                if ($shopUser->loyalty_points > 2000 && $shopUser->loyalty_points < 5000) {
                    # girl boss
                    $shopUser->loyalty_level = 3;
                    $shopUser->loyalty_level_name = "GIRL BOSS";
                }
                if ($shopUser->loyalty_points > 5000 && $shopUser->loyalty_points < 10000) {
                    # baby meh
                    $shopUser->loyalty_level = 4;
                    $shopUser->loyalty_level_name = "BABY MEH";
                }
                if ($shopUser->loyalty_points > 10000) {
                    # meh girl
                    $shopUser->loyalty_level = 5;
                    $shopUser->loyalty_level_name = "MEH GIRL";
                }
                $shopUser->loyalty_points_available = $shopUser->loyalty_points_for_sales 
                    + $shopUser->loyalty_points_for_extras
                    - $shopUser->loyalty_used_points;
                $shopUser->save();
                // historial de puntos y cupones
                $shopUserCupons = ShopUserCupon::whereNull(ShopUserCupon::TABLE_NAME . '.deleted_at')
                    ->where(ShopUserCupon::TABLE_NAME . '.shop_users_id', $shopUser->id)
                    ->get();

                if (isset($params['view'])) {
                    return view($params['view'], compact('shopUser', 'orders', 'shopUserCupons'));
                } else { 
                    return view('index', compact('shopUser', 'orders', 'shopUserCupons'));
                }   
            } catch (\Throwable $th) {
                dd($th);
                return "error de usuario";
            }
        } else {
            return view('nosession');
        }
    }

    public function shopUserUpdate(Request $request)
    {
        $params = $request->all();
        if (isset($params['shop_user_id']) && (int)$params['shop_user_id'] !== 0) {
            $shopUser = ShopUser::find((int)$params['shop_user_id']);
            if (!is_null($shopUser)) {
                if (isset($params['birthday']) 
                    && $params['birthday'] !== ""
                        && is_null($shopUser->birthday)) {
                    $shopUserCupon = ShopUserCupon::whereNull(ShopUserCupon::TABLE_NAME . '.deleted_at')
                                        ->where(ShopUserCupon::TABLE_NAME . '.shop_users_id', $shopUser->id)
                                        ->where(ShopUserCupon::TABLE_NAME . '.code', 'EXTPOINT_BIRTH')
                                        ->first();
                    if (is_null($shopUserCupon)) {
                        $shopUserCupon = new ShopUserCupon();
                        $shopUserCupon->shop_users_id = $shopUser->id;
                        $shopUserCupon->points = 100;
                        $shopUserCupon->code = 'EXTPOINT_BIRTH';
                        $shopUserCupon->name = 'EXTRA POINTS';
                        $shopUserCupon->description = 'Ingresa tu cumpleaños y gana 100 puntos';
                        $shopUserCupon->save();
                        $shopUser->loyalty_points_for_extras = $shopUser->loyalty_points_for_extras + 100;
                    }
                }
                $shopUser->first_name = $params['first_name'];
                $shopUser->last_name = $params['last_name'];
                $shopUser->document_number = $params['document_number'];
                $shopUser->birthday = $params['birthday'];
                $shopUser->phone = $params['phone'];
                $shopUser->default_address = [
                    "address1" => $params['address_name'],
                    "city" => $params['city_name'],
                    "district" => $params['district_name'],
                ];
                $shopUser->save();
            }
            // validacion de datos actualizados
            return redirect('/home?userId=' . $shopUser->shop_id);
        } else {
            dd($th);
            return "error de usuario";
        }
    }

    public function addInstagramPoints(Request $request)
    {
        $params = $request->all();
        $shopUserCupon = null;
        if (isset($params['shop_user_id']) && (int)$params['shop_user_id'] !== 0) {
            $shopUser = ShopUser::where(ShopUser::TABLE_NAME . '.shop_id', (int)$params['shop_user_id'])->first();
            if (!is_null($shopUser)) {
                $shopUserCupon = ShopUserCupon::whereNull(ShopUserCupon::TABLE_NAME . '.deleted_at')
                                    ->where(ShopUserCupon::TABLE_NAME . '.shop_users_id', $shopUser->id)
                                    ->where(ShopUserCupon::TABLE_NAME . '.code', 'EXTPOINT_IG')
                                    ->first();
                if (is_null($shopUserCupon)) {
                    $shopUserCupon = new ShopUserCupon();
                    $shopUserCupon->shop_users_id = $shopUser->id;
                    $shopUserCupon->points = 100;
                    $shopUserCupon->code = 'EXTPOINT_IG';
                    $shopUserCupon->name = 'EXTRA POINTS';
                    $shopUserCupon->description = 'Follow @mehperu en Instagram';
                    $shopUserCupon->save();
                    $shopUser->loyalty_points_for_extras = $shopUser->loyalty_points_for_extras + 100;
                    $shopUser->save();
                }
            }
        }
        return $shopUserCupon;
    }

    public function createCupon(Request $request)
    {
        $params = $request->all();
        if (isset($params['userId']) && (int)$params['userId'] > 0) {    
            $shopUser = ShopUser::whereNull(ShopUser::TABLE_NAME . '.deleted_at')
                ->where(ShopUser::TABLE_NAME . '.shop_id', (int)$params['userId'])
                ->first();
            if (isset($params['cuponId']) && (int)$params['cuponId'] > 0) {    
                $cupon = ShopCupon::find((int)$params['cuponId']);
                if (!is_null($shopUser) && !is_null($cupon)) {
                    return view('create_cupon', compact('shopUser', 'cupon'));
                }
            } else {
                dd($th);
                return "error de usuario";
            }
        }
        return "error de usuario";
    }

    public function storeCupon(Request $request)
    {
        $params = $request->all();
        if (isset($params['userId']) && (int)$params['userId'] > 0) {    
            $shopUser = ShopUser::find((int)$params['userId']);
            if (isset($params['cuponId']) && (int)$params['cuponId'] > 0) {    
                $cupon = ShopCupon::find((int)$params['cuponId']);
                if (!is_null($shopUser) && !is_null($cupon)) {
                    // validar si hay saldo
                    if ($shopUser->loyalty_points_available >= $cupon->points) {
                        // actualizar loyalty points
                        $shopUser->loyalty_used_points = $shopUser->loyalty_used_points + $cupon->points;
                        $shopUser->loyalty_points_available = $shopUser->loyalty_points_available - $cupon->points; 
                        $shopUser->save();
                        // guardar registro de cupon
                        $shopUserCupon = new ShopUserCupon();
                        $shopUserCupon->shop_users_id = $shopUser->id;
                        $shopUserCupon->points = $cupon->points;
                        $shopUserCupon->type = 2;
                        $shopUserCupon->code = 'MGC' . $cupon->id . 'U' . $shopUser->id . 'T' . date('U');
                        $shopUserCupon->name = $cupon->name;
                        $shopUserCupon->description = $cupon->description;
                        $shopUserCupon->save();
                        // SHOPIFY CREAR CUPON
                            // obtener apiclient
                            $shopifyClient = self::getShopifyClient();
                            // cargar usuario
                            $customer = $shopifyClient->getDiscountCodeManager()->create($cupon->price_rule_id, [
                                "code" => $shopUserCupon->code
                            ]);
                        // ENVIAR CUPON AL CORREO ELECTRONICO
                        // historial de puntos y cupones
                        $shopUserCupons = ShopUserCupon::whereNull(ShopUserCupon::TABLE_NAME . '.deleted_at')
                            ->where(ShopUserCupon::TABLE_NAME . '.shop_users_id', $shopUser->id)
                            ->get();
                        $message = "El cupón fue creado correctamente y se envió al correo " . $shopUser->email;
                        return view('result_cupon', compact('message', 'shopUserCupons','shopUser'));
                    } else {
                        // historial de puntos y cupones
                        $shopUserCupons = ShopUserCupon::whereNull(ShopUserCupon::TABLE_NAME . '.deleted_at')
                            ->where(ShopUserCupon::TABLE_NAME . '.shop_users_id', $shopUser->id)
                            ->get();
                        $message = "No se pudo crear el cupón porque no cuentas con puntos disponibles";
                        return view('result_cupon', compact('message', 'shopUserCupons', 'shopUser'));
                    }
                }
            } else {
                return "error de usuario";
            }
        }
        return "error de usuario";
    }
}
