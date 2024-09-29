<?php

    namespace App\Http\Controllers\Admin;

    use App\Enums\TaxType;
    use App\Http\Requests\CustomerRequest;
    use App\Http\Requests\PosOrderRequest;
    use App\Http\Resources\CustomerResource;
    use App\Http\Resources\OrderDetailsResource;
    use App\Models\Order;
    use App\Models\OrderItem;
    use App\Services\CustomerService;
    use App\Services\OrderService;
    use App\Traits\ApiResponse;
    use Exception;
    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Contracts\Routing\ResponseFactory;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\DB;


    class PosController extends AdminController
    {
        use ApiResponse;

        private OrderService    $orderService;
        private CustomerService $customerService;

        public function __construct(OrderService $order , CustomerService $customerService)
        {
            parent::__construct();
            $this->orderService    = $order;
            $this->customerService = $customerService;
            $this->middleware([ 'permission:pos' ])->only('store');
        }

        public function store(PosOrderRequest $request) : Response | OrderDetailsResource | Application | ResponseFactory
        {
//            try {
                return new OrderDetailsResource($this->orderService->posOrderStore($request));
//            } catch ( Exception $exception ) {
//                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
//            }
        }

        public function update(Request $request)
        {
            DB::transaction(function () use ($request) {
                $order = Order::find($request->id);
//        $requestItems = Order::find($request->items);
                $requestItems = json_decode($request->items);
                $order->update([
                    'subtotal' => $request->subtotal ,
                    'total'    => $request->total ,
                    'discount' => $request->discount ,
                ]);
                $itemsArray = [];
                if ( ! blank($requestItems) ) {
                    $i    = 0;
                    $only = [];
                    foreach ( $requestItems as $item ) {
                        $taxId    = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName  = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate  = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType  = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice = $taxType === TaxType::FIXED ? $taxRate : ( $item->total_price * $taxRate ) / 100;
                        if ( isset($item->id) ) {
                            $order_item = OrderItem::find($item->id);
                            $only[]     = $item->id;
                            $order_item->update([
                                'quantity'             => $item->quantity ,
                                'discount'             => (float) $item->discount ,
                                'price'                => $item->item_price ,
                                'item_variations'      => json_encode($item->item_variations) ,
                                'item_extras'          => json_encode($item->item_extras) ,
                                'instruction'          => $item->instruction ,
                                'item_variation_total' => $item->item_variation_total ,
                                'item_extra_total'     => $item->item_extra_total ,
                                'total_price'          => $item->total_price ,
                            ]);
                        } else {
                            $order_item = OrderItem::create([
                                    'order_id'             => $order->id ,
                                    'branch_id'            => $request->branch_id ,
                                    'item_id'              => $item->item_id ,
                                    'quantity'             => $item->quantity ,
                                    'discount'             => (float) $item->discount ,
                                    'price'                => $item->item_price ,
                                    'item_variations'      => json_encode($item->item_variations) ,
                                    'item_extras'          => json_encode($item->item_extras) ,
                                    'instruction'          => $item->instruction ,
                                    'item_variation_total' => $item->item_variation_total ,
                                    'item_extra_total'     => $item->item_extra_total ,
                                    'total_price'          => $item->total_price ,
                                ]
                            );
                            $only[]     = $order_item->id;
                        }
//                    $itemsArray[$i] = [
//                        'order_id'             => $order->id,
//                        'quantity'             => $item->quantity,
//                        'discount'             => (float)$item->discount,
//                        'price'                => $item->item_price,
//                        'item_variations'      => json_encode($item->item_variations),
//                        'item_extras'          => json_encode($item->item_extras),
//                        'instruction'          => $item->instruction,
//                        'item_variation_total' => $item->item_variation_total,
//                        'item_extra_total'     => $item->item_extra_total,
//                        'total_price'          => $item->total_price,
//                    ];
                        $i++;
                    }
                    $order->orderItems()->whereNotIn('order_items.id' , $only)->delete();
                }
//            if (!blank($itemsArray)) {
//                OrderItem::where('order_id', $order->id)->delete();
//                OrderItem::insert($itemsArray);
//            }
            });
//        $order->save();
            return $this->response(success: true , message: 'success');
        }

        public function storeCustomer(
            CustomerRequest $request
        ) : Response | CustomerResource | Application | ResponseFactory {
            try {
                return new CustomerResource($this->customerService->store($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }
    }
