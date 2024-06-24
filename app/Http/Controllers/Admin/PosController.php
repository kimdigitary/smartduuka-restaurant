<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TaxType;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use App\Services\OrderService;
use App\Services\CustomerService;
use App\Http\Requests\PosOrderRequest;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;


class PosController extends AdminController
{
    private OrderService $orderService;
    private CustomerService $customerService;

    public function __construct(OrderService $order, CustomerService $customerService)
    {
        parent::__construct();
        $this->orderService = $order;
        $this->customerService = $customerService;
        $this->middleware(['permission:pos'])->only('store');
    }

    public function store(PosOrderRequest $request): \Illuminate\Http\Response | OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderDetailsResource($this->orderService->posOrderStore($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function update(Request $request)
    {
        $order = Order::find($request->id);
        $requestItems = Order::find($request->items);
        $itemsArray   = [];
        if (!blank($requestItems)) {
            foreach ($requestItems as $item) {
                $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                $itemsArray[$i] = [
                    'quantity'             => $item->quantity,
                    'discount'             => (float)$item->discount,
                    'price'                => $item->item_price,
                    'item_variations'      => json_encode($item->item_variations),
                    'item_extras'          => json_encode($item->item_extras),
                    'instruction'          => $item->instruction,
                    'item_variation_total' => $item->item_variation_total,
                    'item_extra_total'     => $item->item_extra_total,
                    'total_price'          => $item->total_price,
                ];
                $i++;
            }
        }
        if (!blank($itemsArray)) {
            OrderItem::insert($itemsArray);
        }
        $this->order->save();
    }

    public function storeCustomer(
        CustomerRequest $request
    ): \Illuminate\Http\Response|CustomerResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new CustomerResource($this->customerService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
