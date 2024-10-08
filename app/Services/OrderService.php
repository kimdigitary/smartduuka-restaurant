<?php

    namespace App\Services;

    use App\Enums\Ask;
    use App\Enums\OrderItemName;
    use App\Enums\OrderItemStatus;
    use App\Enums\OrderStatus;
    use App\Enums\OrderType;
    use App\Enums\PaymentStatus;
    use App\Enums\TaxType;
    use App\Events\SendOrderGotMail;
    use App\Events\SendOrderGotPush;
    use App\Events\SendOrderGotSms;
    use App\Http\Requests\OrderRequest;
    use App\Http\Requests\OrderStatusRequest;
    use App\Http\Requests\PaginateRequest;
    use App\Http\Requests\PaymentStatusRequest;
    use App\Http\Requests\PosOrderRequest;
    use App\Http\Requests\TableOrderRequest;
    use App\Http\Requests\TableOrderTokenRequest;
    use App\Libraries\AppLibrary;
    use App\Models\Address;
    use App\Models\FrontendOrder;
    use App\Models\Ingredient;
    use App\Models\Item;
    use App\Models\ItemVariation;
    use App\Models\Order;
    use App\Models\OrderAddress;
    use App\Models\OrderItem;
    use App\Models\Stock;
    use App\Models\Tax;
    use App\Models\User;
    use Exception;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Smartisan\Settings\Facades\Settings;

    class OrderService
    {
        public object   $order;
        protected array $orderFilter = [
            'order_serial_no' ,
            'user_id' ,
            'branch_id' ,
            'total' ,
            'order_type' ,
            'order_datetime' ,
            'payment_method' ,
            'payment_status' ,
            'status' ,
            'delivery_boy_id' ,
            'source' ,
            'dining_table_id'
        ];

        protected array $exceptFilter = [
            'excepts'
        ];

        /**
         * @throws Exception
         */
        public function list(PaginateRequest $request)
        {
            try {
                $requests    = $request->all();
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
                $orderColumn = $request->get('order_column') ?? 'id';
                $orderType   = $request->get('order_by') ?? 'desc';

                return Order::with('transaction' , 'orderItems.orderItem.variations' , 'orderItems.orderItem.extras')->where(function ($query) use ($requests) {
                    if ( isset($requests['from_date']) && isset($requests['to_date']) ) {
                        $first_date = Date('Y-m-d' , strtotime($requests['from_date']));
                        $last_date  = Date('Y-m-d' , strtotime($requests['to_date']));
                        $query->whereDate('order_datetime' , '>=' , $first_date)->whereDate(
                            'order_datetime' ,
                            '<=' ,
                            $last_date
                        );
                    }
                    foreach ( $requests as $key => $request ) {
                        if ( in_array($key , $this->orderFilter) ) {
                            if ( $key === "status" ) {
                                $query->where($key , (int) $request);
                            } else {
                                $query->where($key , 'like' , '%' . $request . '%');
                            }
                        }

                        if ( in_array($key , $this->exceptFilter) ) {
                            $explodes = explode('|' , $request);
                            if ( is_array($explodes) ) {
                                foreach ( $explodes as $explode ) {
                                    $query->where('order_type' , '!=' , $explode);
                                }
                            }
                        }
                    }
                })->orderBy($orderColumn , $orderType)->$method(
                    $methodValue
                );
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function chef(PaginateRequest $request)
        {
            try {
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';

                return Order::with('transaction' , 'orderItems.orderItem.variations' , 'orderItems.orderItem.extras')
                            ->where(function ($query) use ($request) {
                                if ( $request->order_type == OrderType::CHEF_BOARD ) {
                                    $query->where('status' , $request->status)
                                          ->orWhere('status' , OrderStatus::PROCESSING);
                                }
                                if ( $request->order_type == OrderType::COMPLETED ) {
                                    $query->Where('status' , OrderStatus::PREPARED);
                                }
                            })
//                ->orderBy('id')->get();
                            ->orderBy('id')->$method(
                        $methodValue
                    );
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function userOrder(PaginateRequest $request , User $user)
        {
            try {
                $requests    = $request->all();
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
                $orderColumn = $request->get('order_column') ?? 'id';
                $orderType   = $request->get('order_by') ?? 'desc';

                return Order::where('order_type' , "!=" , OrderType::POS)->where(function ($query) use ($requests , $user) {
                    $query->where('user_id' , $user->id);
                    foreach ( $requests as $key => $request ) {
                        if ( in_array($key , $this->orderFilter) ) {
                            $query->where($key , 'like' , '%' . $request . '%');
                        }
                        if ( in_array($key , $this->exceptFilter) ) {
                            $explodes = explode('|' , $request);
                            if ( is_array($explodes) ) {
                                foreach ( $explodes as $explode ) {
                                    $query->where('status' , '!=' , $explode);
                                }
                            }
                        }
                    }
                })->orderBy($orderColumn , $orderType)->$method(
                    $methodValue
                );
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function deliveredOrder(PaginateRequest $request , User $user)
        {
            try {
                $requests    = $request->all();
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
                $orderColumn = $request->get('order_column') ?? 'id';
                $orderType   = $request->get('order_by') ?? 'desc';

                return Order::where('delivery_boy_id' , $user->id)->where('order_type' , "!=" , OrderType::POS)->where(
                    function ($query) use ($requests) {
                        foreach ( $requests as $key => $request ) {
                            if ( in_array($key , $this->orderFilter) ) {
                                $query->where($key , 'like' , '%' . $request . '%');
                            }
                            if ( in_array($key , $this->exceptFilter) ) {
                                $explodes = explode('|' , $request);
                                if ( is_array($explodes) ) {
                                    foreach ( $explodes as $explode ) {
                                        $query->where('status' , '!=' , $explode);
                                    }
                                }
                            }
                        }
                    }
                )->orderBy($orderColumn , $orderType)->$method(
                    $methodValue
                );
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function myOrderStore(OrderRequest $request) : object
        {
            try {
                DB::transaction(function () use ($request) {
                    $this->order = Order::create(
                        $request->validated() + [
                            'user_id'          => Auth::user()->id ,
                            'status'           => OrderStatus::PENDING ,
                            'order_datetime'   => date('Y-m-d H:i:s') ,
                            'preparation_time' => Settings::group('site')->get('site_food_preparation_time')
                        ]
                    );

                    $i            = 0;
                    $totalTax     = 0;
                    $itemsArray   = [];
                    $requestItems = json_decode($request->items);
                    $items        = Item::get()->pluck('tax_id' , 'id');
                    $taxes        = AppLibrary::pluck(Tax::get() , 'obj' , 'id');

                    if ( ! blank($requestItems) ) {
                        foreach ( $requestItems as $item ) {
                            $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                            $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                            $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                            $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                            $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ( $item->total_price * $taxRate ) / 100;
                            $itemsArray[$i] = [
                                'order_id'             => $this->order->id ,
                                'branch_id'            => $item->branch_id ,
                                'item_id'              => $item->item_id ,
                                'quantity'             => $item->quantity ,
                                'discount'             => (float) $item->discount ,
                                'tax_name'             => $taxName ,
                                'tax_rate'             => $taxRate ,
                                'tax_type'             => $taxType ,
                                'tax_amount'           => $taxPrice ,
                                'price'                => $item->item_price ,
                                'item_variations'      => json_encode($item->item_variations) ,
                                'item_extras'          => json_encode($item->item_extras) ,
                                'instruction'          => $item->instruction ,
                                'item_variation_total' => $item->item_variation_total ,
                                'item_extra_total'     => $item->item_extra_total ,
                                'total_price'          => $item->total_price ,
                            ];
                            $totalTax       = $totalTax + $taxPrice;
                            $i++;
                        }
                    }

                    if ( ! blank($itemsArray) ) {
                        OrderItem::insert($itemsArray);
                    }

                    $this->order->order_serial_no = date('dmy') . $this->order->id;
                    $this->order->total_tax       = $totalTax;
                    $this->order->save();

                    if ( $request->address_id ) {
                        $address = Address::find($request->address_id);
                        if ( $address ) {
                            OrderAddress::create([
                                'order_id'  => $this->order->id ,
                                'user_id'   => Auth::user()->id ,
                                'label'     => $address->label ,
                                'address'   => $address->address ,
                                'apartment' => $address->apartment ,
                                'latitude'  => $address->latitude ,
                                'longitude' => $address->longitude
                            ]);
                        }
                    }
                });
                return $this->order;
            } catch ( Exception $exception ) {
                DB::rollBack();
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function posOrderStore(PosOrderRequest $request) : object
        {
            try {
                DB::transaction(function () use ($request) {
                    $this->order = Order::create(
                        $request->validated() + [
                            'user_id'          => $request->customer_id ,
                            'status'           => OrderStatus::ACCEPT ,
                            'token'            => $request->token ,
                            'creator_id'       => $request->user()->id ,
//                        'payment_status'   => PaymentStatus::PAID ,
                            'payment_status'   => PaymentStatus::UNPAID ,
                            'order_datetime'   => date('Y-m-d H:i:s') ,
                            'preparation_time' => Settings::group('order_setup')->get('order_setup_food_preparation_time')
                        ]
                    );
//                    PosPayment::create([
//                        'order_id'       => $this->order->id ,
//                        'amount'         => $request->amount ,
//                        'date'           => date('Y-m-d H:i:s' , strtotime($request->date)) ,
//                        'reference_no'   => $request->reference_no ,
//                        'payment_method' => $request->payment_method ,
//                    ]);

                    $i            = 0;
                    $totalTax     = 0;
                    $itemsArray   = [];
                    $requestItems = json_decode($request->items);
                    $items        = Item::get()->pluck('tax_id' , 'id');
                    $taxes        = AppLibrary::pluck(Tax::get() , 'obj' , 'id');

                    if ( ! blank($requestItems) ) {
                        foreach ( $requestItems as $item ) {
                            $taxId    = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                            $taxName  = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                            $taxRate  = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                            $taxType  = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                            $taxPrice = $taxType === TaxType::FIXED ? $taxRate : ( $item->total_price * $taxRate ) / 100;
                            $_item    = Item::find($item->item_id);
//                            $variations = $_item->variations ?? null;
                            $variations = $item->item_variations;

                            if ( $variations && count($variations) > 0 ) {
                                foreach ( $variations as $variation ) {
                                    $item_variation = ItemVariation::find($variation->id);
                                    foreach ( $item_variation->ingredients as $ingredient ) {
                                        $stock = Stock::where([ 'model_type' => Ingredient::class , 'item_id' => $ingredient->id ])->first();
                                        if ( $stock->quantity < ( $ingredient->pivot->quantity * $item->quantity ) ) {
                                            throw new Exception("$_item->name $ingredient->name ingredient out of stock" , 422);
                                        }
                                    }
                                }
                            } else {
                                if ( property_exists($_item , 'ingredients') ) {
                                    foreach ( $_item->ingredients as $ingredient ) {
                                        $stock = Stock::where([ 'model_type' => Ingredient::class , 'item_id' => $ingredient->id ])->first();
                                        if ( $stock->quantity < ( $ingredient->pivot->quantity * $item->quantity ) ) {
                                            throw new Exception("$_item->name $ingredient->name ingredient out of stock" , 422);
                                        }
                                    }
                                }
                            }

                            $itemsArray[$i] = [
                                'order_id'             => $this->order->id ,
                                'branch_id'            => $item->branch_id ,
                                'item_id'              => $item->item_id ,
                                'quantity'             => $item->quantity ,
                                'discount'             => (float) $item->discount ,
                                'status'               => $_item->name == OrderItemName::ADULTS || $_item->name == OrderItemName::FIVE_TO_NINE ||
                                $_item->name == OrderItemName::BELOW_5 ?
                                    OrderItemStatus::COMPLETED : OrderItemStatus::PENDDING ,
                                'tax_name'             => $taxName ,
                                'tax_rate'             => $taxRate ,
                                'tax_type'             => $taxType ,
                                'tax_amount'           => $taxPrice ,
                                'price'                => $item->item_price ,
                                'item_variations'      => json_encode($item->item_variations) ,
                                'item_extras'          => json_encode($item->item_extras) ,
                                'instruction'          => $item->instruction ,
                                'item_variation_total' => $item->item_variation_total ,
                                'item_extra_total'     => $item->item_extra_total ,
                                'total_price'          => $item->total_price ,
                            ];

                            $totalTax = $totalTax + $taxPrice;
                            $i++;
                        }
                    }


                    if ( ! blank($itemsArray) ) {
                        OrderItem::insert($itemsArray);
                    }

                    $this->order->order_serial_no = date('dmy') . $this->order->id;
                    $this->order->total_tax       = $totalTax;
                    $this->order->save();
                });
                return $this->order;
            } catch ( Exception $exception ) {
                DB::rollBack();
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }


        /**
         * @throws Exception
         */
        public function tableOrderStore(TableOrderRequest $request) : object
        {
            try {
                DB::transaction(function () use ($request) {
                    $this->order = FrontendOrder::create(
                        $request->validated() + [
                            'user_id'          => $request->customer_id ,
                            'status'           => OrderStatus::PENDING ,
                            'order_datetime'   => date('Y-m-d H:i:s') ,
                            'preparation_time' => Settings::group('site')->get('site_food_preparation_time')
                        ]
                    );

                    $i            = 0;
                    $totalTax     = 0;
                    $itemsArray   = [];
                    $requestItems = json_decode($request->items);
                    $items        = Item::get()->pluck('tax_id' , 'id');
                    $taxes        = AppLibrary::pluck(Tax::get() , 'obj' , 'id');

                    if ( ! blank($requestItems) ) {
                        foreach ( $requestItems as $item ) {
                            $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                            $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                            $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                            $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                            $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ( $item->total_price * $taxRate ) / 100;
                            $itemsArray[$i] = [
                                'order_id'             => $this->order->id ,
                                'branch_id'            => $item->branch_id ,
                                'item_id'              => $item->item_id ,
                                'quantity'             => $item->quantity ,
                                'discount'             => (float) $item->discount ,
                                'tax_name'             => $taxName ,
                                'tax_rate'             => $taxRate ,
                                'tax_type'             => $taxType ,
                                'tax_amount'           => $taxPrice ,
                                'price'                => $item->item_price ,
                                'item_variations'      => json_encode($item->item_variations) ,
                                'item_extras'          => json_encode($item->item_extras) ,
                                'instruction'          => $item->instruction ,
                                'item_variation_total' => $item->item_variation_total ,
                                'item_extra_total'     => $item->item_extra_total ,
                                'total_price'          => $item->total_price ,
                            ];
                            $totalTax       = $totalTax + $taxPrice;
                            $i++;
                        }
                    }

                    if ( ! blank($itemsArray) ) {
                        OrderItem::insert($itemsArray);
                    }

                    $this->order->order_serial_no = date('dmy') . $this->order->id;
                    $this->order->total_tax       = $totalTax;
                    $this->order->save();

                    SendOrderGotMail::dispatch([ 'order_id' => $this->order->id ]);
                    SendOrderGotSms::dispatch([ 'order_id' => $this->order->id ]);
                    SendOrderGotPush::dispatch([ 'order_id' => $this->order->id ]);
                });
                return $this->order;
            } catch ( Exception $exception ) {
                DB::rollBack();
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function show(Order $order , $auth = false) : Order | array
        {
            try {
                if ( $auth ) {
                    if ( $order->user_id == Auth::user()->id ) {
                        return $order;
                    } else {
                        return [];
                    }
                } else {
                    return $order;
                }
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function orderDetails(User $user , Order $order) : Order | array
        {
            try {
                if ( $order->user_id == $user->id ) {
                    return $order;
                } else {
                    return [];
                }
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function changeStatus(Order $order , $auth = false , OrderStatusRequest $request) : Order | array
        {
            try {
                if ( $auth ) {
                    if ( $order->user_id == Auth::user()->id ) {
                        if ( $request->reason ) {
                            $order->reason = $request->reason;
                        }
                        if ( $request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED ) {
                            if ( $order->transaction ) {
                                app(PaymentService::class)->cashBack(
                                    $order ,
                                    'credit' ,
                                    rand(111111111111111 , 99999999999999)
                                );
                            }
                        }
                        $order->status = $request->status;
                        $order->save();
                    }
                } else {
                    if ( $request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED ) {
                        $request->validate([
                            'reason' => 'required|max:700' ,
                        ]);

                        if ( $request->reason ) {
                            $order->reason = $request->reason;
                        }

                        if ( $order->transaction ) {
                            app(PaymentService::class)->cashBack(
                                $order ,
                                'credit' ,
                                rand(111111111111111 , 99999999999999)
                            );
                        }
                    }

                    if ( $request->status == OrderStatus::PROCESSING || $request->status == OrderStatus::DELIVERED ) {
                        if ( $request->orderItemID ) {
                            $order_item = OrderItem::find($request->orderItemID);
                            $order_item->update([ 'status' => $request->orderItemStatus ]);
                        }
                        $order->status = $request->status;
                        $order->save();
                    }
                    if ( $request->status == OrderStatus::PREPARED ) {
                        foreach ( $order->items as $order_item ) {
//                            $item_variations = json_decode($order_item->item_variations);
                            $item_variations = $order_item->variations;
                            if ( $item_variations ) {
                                foreach ( $item_variations as $item_variation ) {
                                    foreach ( ItemVariation::find($item_variation->id)->ingredients as $ingredient ) {
                                        $stock = Stock::where([ 'model_type' => Ingredient::class , 'item_id' => $ingredient->id ])->first();
                                        if ( $stock->quantity > $ingredient->pivot->quantity ) {
                                            $stock->decrement('quantity' , $ingredient->pivot->quantity);
                                        }
                                    }
                                }
                            }
//                            if ( $item_addons ) {
//                                foreach ( $item_addons as $item_addon ) {
//                                    foreach ( ItemAddon::find($item_addon->id)->ingredients as $ingredient ) {
//                                        $stock = Stock::where([ 'model_type' => Ingredient::class , 'item_id' => $ingredient->id ])->first();
//                                        if ( $stock->quantity > $ingredient->pivot->quantity ) {
//                                            $stock->decrement('quantity' , $ingredient->pivot->quantity);
//                                        }
//                                    }
//                                }
//                            }
                            if ( ! $item_variations ) {
                                if ( $order_item->is_stockable == Ask::NO && $order_item->ingredients ) {
                                    foreach ( $order_item->ingredients as $ingredient ) {
                                        Stock::where([ 'model_type' => Ingredient::class , 'item_id' => $ingredient->id ])->decrement('quantity' , $ingredient->pivot->quantity);
                                    }
                                }
                            }
                        }

                        $order->status = $request->status;
                        $order->save();
                    }
                    if ( $request->status == OrderStatus::ACCEPT ) {
                        if ( $request->orderItemID ) {
                            OrderItem::find($request->orderItemID)->update([ 'status' => $request->orderItemStatus ]);
                        }
                        $order->status = $request->status;
                        $order->save();
                    }
                }
                return $order;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function changePaymentStatus(Order $order , $auth = false , PaymentStatusRequest $request) : Order | array
        {
            try {
                if ( $auth ) {
                    if ( $order->user_id == Auth::user()->id ) {
                        $order->payment_status = $request->payment_status;
                        $order->save();
                        return $order;
                    } else {
                        return [];
                    }
                } else {
                    $order->payment_status = $request->payment_status;
                    $order->save();
                    return $order;
                }
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }


        public function tokenCreate(Order $order , $auth = false , TableOrderTokenRequest $request) : Order | array
        {
            try {
                if ( $auth ) {
                    if ( $order->user_id == Auth::user()->id ) {
                        $order->token = $request->token;
                        $order->save();
                        return $order;
                    } else {
                        return [];
                    }
                } else {
                    $order->token = $request->token;
                    $order->save();
                    return $order;
                }
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function destroy(Order $order)
        {
            try {
                DB::transaction(function () use ($order) {
                    $order->address()?->delete();
                    $order->orderItems()?->delete();
                    $order->delete();
                });
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }
    }
