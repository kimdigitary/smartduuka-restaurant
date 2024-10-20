<?php

    namespace App\Services;

    use App\Enums\Ask;
    use App\Enums\PaymentStatus;
    use App\Enums\PurchasePaymentStatus;
    use App\Enums\PurchaseStatus;
    use App\Enums\PurchaseType;
    use App\Enums\Status;
    use App\Http\Requests\PaginateRequest;
    use App\Http\Requests\PurchasePaymentRequest;
    use App\Http\Requests\PurchaseRequest;
    use App\Http\Requests\StorePosPaymentRequest;
    use App\Libraries\QueryExceptionLibrary;
    use App\Models\Ingredient;
    use App\Models\Item;
    use App\Models\Order;
    use App\Models\PosPayment;
    use App\Models\Product;
    use App\Models\ProductVariation;
    use App\Models\Purchase;
    use App\Models\PurchasePayment;
    use App\Models\Stock;
    use App\Models\StockTax;
    use App\Models\Tax;
    use Exception;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class PurchaseService
    {
        public object   $purchase;
        public object   $stock;
        protected array $purchaseFilter = [
            'supplier_id' ,
            'date' ,
            'reference_no' ,
            'status' ,
            'total' ,
            'note' ,
            'except'
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
                $orderType   = $request->get('order_type') ?? 'desc';

                return Purchase::with('supplier')->where(function ($query) use ($requests) {
                    $query->where('type' , $requests['type']);
                    foreach ( $requests as $key => $request ) {
                        if ( in_array($key , $this->purchaseFilter) ) {
                            if ( $key == "except" ) {
                                $explodes = explode('|' , $request);
                                if ( count($explodes) ) {
                                    foreach ( $explodes as $explode ) {
                                        $query->where('id' , '!=' , $explode);
                                    }
                                }
                            } else {
                                if ( $key == "supplier_id" || $key == 'status' ) {
                                    $query->where($key , $request);
                                } else if ( $key == "date" && ! empty($request) ) {
                                    $date_start = date('Y-m-d 00:00:00' , strtotime($request));
                                    $date_end   = date('Y-m-d 23:59:59' , strtotime($request));
                                    $query->where($key , '>=' , $date_start)->where($key , '<=' , $date_end);
                                } else {
                                    $query->where($key , 'like' , '%' . $request . '%');
                                }
                            }
                        }
                    }
                })->orderBy($orderColumn , $orderType)->$method($methodValue);
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function ingreidentList(PaginateRequest $request)
        {

            try {
                $requests    = $request->all();
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
                $orderColumn = $request->get('order_column') ?? 'id';
                $orderType   = $request->get('order_type') ?? 'desc';

                return Purchase::with('supplier')->where(function ($query) use ($requests) {
                    $query->where('type' , $requests['type']);
                    foreach ( $requests as $key => $request ) {
                        if ( in_array($key , $this->purchaseFilter) ) {
                            if ( $key == "except" ) {
                                $explodes = explode('|' , $request);
                                if ( count($explodes) ) {
                                    foreach ( $explodes as $explode ) {
                                        $query->where('id' , '!=' , $explode);
                                    }
                                }
                            } else {
                                if ( $key == "supplier_id" || $key == 'status' ) {
                                    $query->where($key , $request);
                                } else if ( $key == "date" && ! empty($request) ) {
                                    $date_start = date('Y-m-d 00:00:00' , strtotime($request));
                                    $date_end   = date('Y-m-d 23:59:59' , strtotime($request));
                                    $query->where($key , '>=' , $date_start)->where($key , '<=' , $date_end);
                                } else {
                                    $query->where($key , 'like' , '%' . $request . '%');
                                }
                            }
                        }
                    }
                })->orderBy($orderColumn , $orderType)->$method($methodValue);
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function store(PurchaseRequest $request) : object
        {
            try {
                DB::transaction(function () use ($request) {
                    $purchase       = Purchase::create([
                        'supplier_id'    => $request->supplier_id ,
                        'date'           => date('Y-m-d H:i:s' , strtotime($request->date)) ,
                        'reference_no'   => $request->reference_no ,
                        'subtotal'       => $request->subtotal ,
                        'type'           => PurchaseType::ITEM ,
                        'tax'            => $request->tax ,
                        'discount'       => $request->discount ,
                        'balance'        => $request->amount ? $request->amount : $request->total ,
                        'total'          => $request->total ,
                        'note'           => $request->note ? $request->note : "" ,
                        'status'         => $request->status ,
                        'payment_status' => PurchasePaymentStatus::PENDING ,
                    ]);
                    $this->purchase = $purchase;

                    if ( $request->add_payment == Ask::YES ) {
                        $purchasePayment = PurchasePayment::create([
                            'purchase_id'    => $purchase->id ,
                            'date'           => date('Y-m-d H:i:s' , strtotime($request->payment_date)) ,
                            'reference_no'   => $request->reference_no ,
                            'amount'         => $request->amount ,
                            'payment_method' => $request->payment_method ,
                        ]);
                    }

                    if ( $request->products ) {
                        $model_id = $this->purchase->id;
                        $products = json_decode($request->products , true);
                        $taxes    = Tax::all()->keyBy('id');
                        foreach ( $products as $product ) {
                            Stock::create([
                                'model_type' => Purchase::class ,
                                'model_id'   => $model_id ,
                                'item_type'  => Item::class ,
                                'type'       => PurchaseType::ITEM ,
                                'item_id'    => $product['product_id'] ,
                                'price'      => $product['price'] ,
                                'quantity'   => $product['quantity'] ,
                                'discount'   => $product['total_discount'] ,
                                'tax'        => $product['total_tax'] ,
                                'subtotal'   => $product['subtotal'] ,
                                'total'      => $product['total'] ,
                                'status'     => $request->status == PurchaseStatus::RECEIVED ? Status::ACTIVE : Status::INACTIVE
                            ]);
                        }
                    }

                    if ( $request->file ) {
                        $this->purchase->addMediaFromRequest('file')->toMediaCollection('purchase');
                    }
                    if ( $request->payment_file ) {
                        $purchasePayment->addMediaFromRequest('payment_file')->toMediaCollection('purchase_payment');
                    }
                    $checkPurchasePayment = PurchasePayment::where('purchase_id' , $purchase->id)->sum('amount');
                    if ( $checkPurchasePayment == $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::FULLY_PAID;
                        $purchase->save();
                    }
                    if ( $checkPurchasePayment < $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::PARTIAL_PAID;
                        $purchase->save();
                    }
                });
                return $this->purchase;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function storeIngredient(PurchaseRequest $request) : object
        {
            try {
                DB::transaction(function () use ($request) {
                    $purchase       = Purchase::create([
                        'supplier_id'    => $request->supplier_id ,
                        'date'           => date('Y-m-d H:i:s' , strtotime($request->date)) ,
                        'reference_no'   => $request->reference_no ,
                        'subtotal'       => $request->subtotal ,
                        'tax'            => $request->tax ,
                        'type'           => PurchaseType::INGREDIENT ,
                        'discount'       => $request->discount ,
                        'balance'        => $request->amount ? $request->amount : $request->total ,
                        'total'          => $request->total ,
                        'note'           => $request->note ? $request->note : "" ,
                        'status'         => $request->status ,
                        'payment_status' => PurchasePaymentStatus::PENDING ,
                    ]);
                    $this->purchase = $purchase;
                    if ( $request->add_payment == Ask::YES ) {
                        $purchasePayment = PurchasePayment::create([
                            'purchase_id'    => $purchase->id ,
                            'date'           => date('Y-m-d H:i:s' , strtotime($request->payment_date)) ,
                            'reference_no'   => $request->reference_no ,
                            'amount'         => $request->amount ,
                            'payment_method' => $request->payment_method ,
                        ]);
                    }

                    if ( $request->products ) {
                        $products = json_decode($request->products , true);
                        foreach ( $products as $product ) {
                            $stock = Stock::where('model_type' , Ingredient::class)
                                          ->where('model_id' , $product['item_id'])
                                          ->first();
                            if ( $stock ) {
                                return $stock->increment('quantity' , $product['quantity']);
                            } else {
                                Stock::create([
                                    'model_type' => Ingredient::class ,
                                    'model_id'   => $product['item_id'] ,
                                    'item_type'  => Ingredient::class ,
                                    'item_id'    => $product['item_id'] ,
                                    'price'      => $product['price'] ,
                                    'type'       => PurchaseType::INGREDIENT ,
                                    'quantity'   => $product['quantity'] ,
                                    'discount'   => $product['total_discount'] ,
                                    'tax'        => $product['total_tax'] ,
                                    'subtotal'   => $product['subtotal'] ,
                                    'total'      => $product['total'] ,
                                    'status'     => $request->status == PurchaseStatus::RECEIVED ? Status::ACTIVE : Status::INACTIVE
                                ]);
                            }
                        }
                    }

                    if ( $request->file ) {
                        $this->purchase->addMediaFromRequest('file')->toMediaCollection('purchase');
                    }
                    if ( $request->payment_file ) {
                        $purchasePayment->addMediaFromRequest('payment_file')->toMediaCollection('purchase_payment');
                    }
                    $checkPurchasePayment = PurchasePayment::where('purchase_id' , $purchase->id)->sum('amount');
                    if ( $checkPurchasePayment == $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::FULLY_PAID;
                        $purchase->save();
                    }
                    if ( $checkPurchasePayment < $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::PARTIAL_PAID;
                        $purchase->save();
                    }
                });
                return $this->purchase;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function storeStock(PurchaseRequest $request) : object
        {
            try {
                DB::transaction(function () use ($request) {
                    $purchase       = Purchase::create([
                        'supplier_id'    => $request->supplier_id ,
                        'date'           => date('Y-m-d H:i:s' , strtotime($request->date)) ,
                        'reference_no'   => $request->reference_no ,
                        'subtotal'       => $request->subtotal ,
                        'tax'            => $request->tax ,
                        'type'           => PurchaseType::ITEM ,
                        'discount'       => $request->discount ,
                        'balance'        => $request->amount ? $request->amount : $request->total ,
                        'total'          => $request->total ,
                        'note'           => $request->note ? $request->note : "" ,
                        'status'         => $request->status ,
                        'payment_status' => PurchasePaymentStatus::PENDING ,
                    ]);
                    $this->purchase = $purchase;
                    if ( $request->add_payment == Ask::YES ) {
                        $purchasePayment = PurchasePayment::create([
                            'purchase_id'    => $purchase->id ,
                            'date'           => date('Y-m-d H:i:s' , strtotime($request->payment_date)) ,
                            'reference_no'   => $request->reference_no ,
                            'amount'         => $request->amount ,
                            'payment_method' => $request->payment_method ,
                        ]);
//                        Expense::create([
//                            'name'          => 'Purchased Items' ,
//                            'amount'        => $request->total ,
//                            'date'          => date('Y-m-d H:i:s' , strtotime($request->date)) ,
//                            'paymentMethod' => $request->payment_method ,
//                            'referenceNo'   => $request->reference_no ,
//                            'isRecurring'   => 0 ,
//                            'user_id'       => auth()->user()->id ,
//                            'repetitions'   => 0 ,
//                            'repeats_on'    => null ,
//                            'paid'          => $request->amount ,
//                            'paid_on'       => date('Y-m-d H:i:s' , strtotime($request->payment_date))
//                        ]);
                    }

                    if ( $request->products ) {
                        $products = json_decode($request->products , true);
                        foreach ( $products as $product ) {
                            $stock = Stock::where('model_type' , Item::class)
                                          ->where('model_id' , $product['item_id'])
                                          ->first();
                            if ( $stock ) {
                                return $stock->increment('quantity' , $product['quantity']);
                            } else {
                                Stock::create([
                                    'model_type' => Item::class ,
                                    'model_id'   => $product['item_id'] ,
                                    'item_type'  => Item::class ,
                                    'item_id'    => $product['item_id'] ,
                                    'price'      => $product['price'] ,
                                    'type'       => PurchaseType::ITEM ,
                                    'quantity'   => $product['quantity'] ,
                                    'discount'   => $product['total_discount'] ,
                                    'tax'        => $product['total_tax'] ,
                                    'subtotal'   => $product['subtotal'] ,
                                    'total'      => $product['total'] ,
                                    'status'     => $request->status == PurchaseStatus::RECEIVED ? Status::ACTIVE : Status::INACTIVE
                                ]);
                            }
                        }
                    }

                    if ( $request->file ) {
                        $this->purchase->addMediaFromRequest('file')->toMediaCollection('purchase');
                    }
                    if ( $request->payment_file ) {
                        $purchasePayment->addMediaFromRequest('payment_file')->toMediaCollection('purchase_payment');
                    }
                    $checkPurchasePayment = PurchasePayment::where('purchase_id' , $purchase->id)->sum('amount');
                    if ( $checkPurchasePayment == $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::FULLY_PAID;
                        $purchase->save();
                    }
                    if ( $checkPurchasePayment < $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::PARTIAL_PAID;
                        $purchase->save();
                    }
                });
                return $this->purchase;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function show(Purchase $purchase)
        {
            try {
                return $purchase->load('media');
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function showPos(Order $order) : Order
        {
            try {
                return $order;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function edit(Purchase $purchase) : Purchase
        {
            try {
                return $purchase;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function update(PurchaseRequest $request , Purchase $purchase) : object
        {
            try {
                DB::transaction(function () use ($request , $purchase) {
                    $purchase->update([
                        'supplier_id'  => $request->supplier_id ,
                        'date'         => date('Y-m-d H:i:s' , strtotime($request->date)) ,
                        'reference_no' => $request->reference_no ,
                        'subtotal'     => $request->subtotal ,
                        'tax'          => $request->tax ,
                        'discount'     => $request->discount ,
                        'total'        => $request->total ,
                        'note'         => $request->note ? $request->note : "" ,
                        'status'       => $request->status
                    ]);

                    if ( $request->products ) {
                        $model_id = $purchase->id;
                        $products = json_decode($request->products , true);
                        if ( $purchase->stocks ) {
                            $stockIds = $purchase->stocks->pluck('id');
                            if ( ! blank($stockIds) ) {
                                StockTax::whereIn('stock_id' , $stockIds)->delete();
                            }
                            $purchase->stocks()->delete();
                        }
                        $taxes = Tax::all()->keyBy('id');
                        foreach ( $products as $product ) {
                            $stock = Stock::create([
                                'model_type'      => Purchase::class ,
                                'model_id'        => $model_id ,
                                'item_type'       => $product['is_variation'] ? ProductVariation::class : Product::class ,
                                'item_id'         => $product['item_id'] ,
                                'variation_names' => $product['variation_names'] ,
                                'product_id'      => $product['product_id'] ,
                                'price'           => $product['price'] ,
                                'quantity'        => $product['quantity'] ,
                                'discount'        => $product['total_discount'] ,
                                'tax'             => $product['total_tax'] ,
                                'subtotal'        => $product['subtotal'] ,
                                'total'           => $product['total'] ,
                                'sku'             => $product['sku'] ,
                                'status'          => $request->status == PurchaseStatus::RECEIVED ? Status::ACTIVE : Status::INACTIVE
                            ]);
                            if ( isset($product['tax_id']) && count($product['tax_id']) > 0 ) {
                                foreach ( $product['tax_id'] as $tax_id ) {
                                    if ( isset($taxes[$tax_id]) ) {
                                        $tax = $taxes[$tax_id];
                                        StockTax::create([
                                            'stock_id'   => $stock->id ,
                                            'product_id' => $product['product_id'] ,
                                            'tax_id'     => $tax->id ,
                                            'name'       => $tax->name ,
                                            'code'       => $tax->code ,
                                            'tax_rate'   => $tax->tax_rate ,
                                            'tax_amount' => ( $tax->tax_rate * ( $product['price'] * $product['quantity'] ) ) / 100 ,
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    if ( $request->file ) {
                        $file = $purchase->getFirstMedia('purchase');
                        if ( isset($file) ) {
                            $file->delete();
                        }
                        $purchase->addMediaFromRequest('file')->toMediaCollection('purchase');
                    }
                });
                return $purchase;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        /**
         * @throws Exception
         */
        public function destroy(Purchase $purchase) : void
        {
            try {
                DB::transaction(function () use ($purchase) {
                    if ( $purchase->stocks ) {
                        $stockIds = $purchase->stocks->pluck('id');
                        if ( ! blank($stockIds) ) {
                            StockTax::whereIn('stock_id' , $stockIds)->delete();
                        }
                        $purchase->stocks()->delete();
                    }
                    $file = $purchase->getFirstMedia('purchase');
                    $file?->delete();
                    $purchase->delete();
                });
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception(QueryExceptionLibrary::message($exception) , 422);
            }
        }

        public function downloadAttachment(Purchase $purchase)
        {
            return $purchase->getMedia('purchase')->first();
        }

        /**
         * @throws Exception
         */
        public function payment(PurchasePaymentRequest $request , Purchase $purchase) : object
        {
            try {
                DB::transaction(function () use ($request , $purchase) {
                    $purchasePayment = PurchasePayment::create([
                        'purchase_id'    => $purchase->id ,
                        'date'           => date('Y-m-d H:i:s' , strtotime($request->date)) ,
                        'reference_no'   => $request->reference_no ,
                        'amount'         => $request->amount ,
                        'payment_method' => $request->payment_method ,
                    ]);

                    if ( $request->file ) {
                        $purchasePayment->addMediaFromRequest('file')->toMediaCollection('purchase_payment');
                    }
                    if ( $request->payment_file ) {
                        $purchasePayment->addMediaFromRequest('payment_file')->toMediaCollection('purchase_payment');
                    }

                    $checkPurchasePayment = PurchasePayment::where('purchase_id' , $purchase->id)->sum('amount');

                    if ( $checkPurchasePayment == $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::FULLY_PAID;
                        $purchase->save();
                    }

                    if ( $checkPurchasePayment < $purchase->total ) {
                        $purchase->payment_status = PurchasePaymentStatus::PARTIAL_PAID;
                        $purchase->save();
                    }
                });
                return $purchase;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function pos(StorePosPaymentRequest $request , Order $order) : object
        {
            try {
                DB::transaction(function () use ($request , $order) {
                    $purchasePayment       = PosPayment::create([
                        'order_id'       => $order->id ,
                        'date'           => date('Y-m-d H:i:s' , strtotime($request->date)) ,
                        'reference_no'   => $request->reference_no ,
                        'amount'         => $request->amount ,
                        'payment_method' => $request->payment_method ,
                    ]);
                    $order->payment_method = $request->payment_method;
                    $order->change         = $request->change;
                    $order->paid           = $request->paid;

                    if ( $request->file ) {
                        $purchasePayment->addMediaFromRequest('file')->toMediaCollection('pos_payment');
                    }
                    if ( $request->payment_file ) {
                        $purchasePayment->addMediaFromRequest('payment_file')->toMediaCollection('pos_payment');
                    }

                    $checkPosPayment = PosPayment::where('order_id' , $order->id)->sum('amount');

                    if ( $checkPosPayment == $order->total ) {
                        $order->payment_status = PaymentStatus::PAID;
                        $order->save();
                    }

                    if ( $checkPosPayment < $order->total ) {
                        $order->payment_status = PaymentStatus::UNPAID;
                        $order->save();
                    }
                });
                return $order;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function paymentHistory(PaginateRequest $request , Purchase $purchase) : object
        {
            try {
                $requests    = $request->all();
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
                $orderColumn = $request->get('order_column') ?? 'id';
                $orderType   = $request->get('order_type') ?? 'desc';
//                return PurchasePayment::where('purchase_id' , $purchase->id)->get();
                return PurchasePayment::where('purchase_id' , $purchase->id)->orderBy($orderColumn , $orderType)->$method($methodValue);;
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function posPaymentHistory(PaginateRequest $request , Order $order) : object
        {
            try {
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
                $orderColumn = $request->get('order_column') ?? 'id';
                $orderType   = $request->get('order_type') ?? 'desc';
//                return PurchasePayment::where('purchase_id' , $purchase->id)->get();
                return PosPayment::where('order_id' , $order->id)->orderBy($orderColumn , $orderType)->$method($methodValue);
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

//        public function list(PaginateRequest $request)
//        {
//            try {
//                $requests    = $request->all();
//                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
//                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
//                $orderColumn = $request->get('order_column') ?? 'id';
//                $orderType   = $request->get('order_type') ?? 'desc';
//
//                return Purchase::with('supplier')->where(function ($query) use ($requests) {
//                    $query->where('type' , $requests['type']);
//                    foreach ( $requests as $key => $request ) {
//                        if ( in_array($key , $this->purchaseFilter) ) {
//                            if ( $key == 'except' ) {
//                                $explodes = explode('|' , $request);
//                                if ( count($explodes) ) {
//                                    foreach ( $explodes as $explode ) {
//                                        $query->where('id' , '!=' , $explode);
//                                    }
//                                }
//                            } else {
//                                if ( $key == 'supplier_id' || $key == 'status' ) {
//                                    $query->where($key , $request);
//                                } else if ( $key == 'date' && ! empty($request) ) {
//                                    $date_start = date('Y-m-d 00:00:00' , strtotime($request));
//                                    $date_end   = date('Y-m-d 23:59:59' , strtotime($request));
//                                    $query->where($key , '>=' , $date_start)->where($key , '<=' , $date_end);
//                                } else {
//                                    $query->where($key , 'like' , '%' . $request . '%');
//                                }
//                            }
//                        }
//                    }
//                })->orderBy($orderColumn , $orderType)->$method($methodValue);
//            } catch ( Exception $exception ) {
//                Log::info($exception->getMessage());
//                throw new Exception($exception->getMessage() , 422);
//            }
//        }

        public function storeStock1(PurchaseRequest $request) : object
        {
            try {
                DB::transaction(function () use ($request) {
                    if ( $request->products ) {
                        $products = json_decode($request->products , true);
                        foreach ( $products as $product ) {
//                        $this->itemStock = Stock::create([
                            $existing_stock = Stock::where('item_id' , $product['product_id'])
                                                   ->where('model_type' , Item::class)->first();
                            if ( $existing_stock ) {
                                $existing_stock->update([
                                    'quantity' => $existing_stock->quantity + $product['quantity'] ,
                                    'subtotal' => $existing_stock->subtotal + $product['subtotal'] ,
                                    'total'    => $existing_stock->total + $product['total'] ,
                                ]);
                                $this->stock = $existing_stock;
                            } else {
                                $stock       = Stock::create([
                                    'model_type' => Item::class ,
                                    'item_type'  => Item::class ,
                                    'model_id'   => $product['product_id'] ,
                                    'item_id'    => $product['product_id'] ,
                                    'price'      => $product['price'] ,
                                    'quantity'   => $product['quantity'] ,
                                    'discount'   => $product['total_discount'] ,
                                    'tax'        => $product['total_tax'] ,
                                    'subtotal'   => $product['subtotal'] ,
                                    'total'      => $product['total'] ,
                                    'status'     => $request->status == PurchaseStatus::RECEIVED ? Status::ACTIVE : Status::INACTIVE
                                ]);
                                $this->stock = $stock;
                            }

//                            $tax = Tax::find($product['tax_id']);
//                            StockTax::create([
//                                'stock_id'   => $this->stock->id ,
//                                'item_id'    => $product['product_id'] ,
//                                'tax_id'     => $product['tax_id'] ,
//                                'name'       => $tax->name ,
//                                'code'       => $tax->code ,
//                                'tax_rate'   => $tax->tax_rate ,
//                                'tax_amount' => ( $tax->tax_rate * ( $product['price'] * $product['quantity'] ) ) / 100 ,
//                            ]);
                        }
                    }
                });
                return $this->stock;
            } catch ( Exception $exception ) {
                info($exception->getMessage());
                Log::info($exception->getMessage());
                DB::rollBack();
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function paymentDownloadAttachment(PurchasePayment $purchasePayment)
        {
            return $purchasePayment->getMedia('purchase_payment')->first();
        }

        /**
         * @throws Exception
         */
        public function paymentDestroy(Purchase $purchase , PurchasePayment $purchasePayment) : void
        {
            try {
                $purchasePayment->delete();
                $checkPurchasePayment = PurchasePayment::where('purchase_id' , $purchase->id)->sum('amount');
                if ( $checkPurchasePayment < $purchase->total && $checkPurchasePayment !== 0 ) {
                    $purchase->payment_status = PurchasePaymentStatus::PARTIAL_PAID;
                    $purchase->save();
                }

                if ( $checkPurchasePayment == 0 ) {
                    $purchase->payment_status = PurchasePaymentStatus::PENDING;
                    $purchase->save();
                }
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }
    }
