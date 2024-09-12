<?php

namespace App\Services;

use App\Enums\Status;
use App\Http\Requests\PaginateRequest;
use App\Models\Item;
use App\Models\Stock;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class StockService
{
    public $items;
    public $links;
    protected $stockFilter = [
        'name',
        'status',
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            $stocks = Stock::with('item')->where(function ($query) use ($requests) {
                $query->where('item_type', Item::class);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->stockFilter)) {
                        if ($key == "product_name") {
                            $query->whereHas('item', function ($query) use ($request) {
                                $query->where('name', 'like', '%' . $request . '%');
                            })->get();
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->get();

            if (!blank($stocks)) {
                $stocks->groupBy('item_id')?->map(function ($item) {
                    $item->groupBy('item_id')?->map(function ($item) {
                        $this->items[] = [
                            'item_id'      => $item->first()['item_id'],
                            'product_name' => $item->first()['item']['name'],
                            'status'       => $item->first()['item']['status'],
                            'itemStock'    => $item->sum('quantity'),
                        ];
                    });
                });
            } else {
                $this->items = [];
            }

            if ($method == 'paginate') {
                return $this->paginate($this->items, $methodValue, null, URL::to('/') . '/api/admin/itemStock');
            }

            return $this->items;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function listIngredients(PaginateRequest $request)
    {
//        try {
        $requests = $request->all();
        $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
        $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
        $orderColumn = $request->get('order_column') ?? 'id';
        $orderType = $request->get('order_type') ?? 'desc';

        $stocks = Stock::with('ingredient')->where('status', Status::ACTIVE)->where(function ($query) use ($requests) {
            foreach ($requests as $key => $request) {
                if (in_array($key, $this->stockFilter)) {
                    if ($key == 'product_name') {
                        $query->whereHas('product', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request . '%');
                        })->get();
                    } else {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            }
        })->orderBy($orderColumn, $orderType)->get();

        if (!blank($stocks)) {
            $stocks->groupBy('item_id')?->map(function ($item) {
                $item->groupBy('item_id')?->map(function ($item) {
                    $stock_item = [
                        'item_id'      => $item->first()['item_id'],
                        'product_name' => $item->first()['item']['name'],
                        'status'       => $item->first()['item']['status'],
                        'itemStock'    => $item->sum('quantity'),
                    ];
//                    $this->items[] = [
//                        'item_id'      => $item->first()['item_id'],
//                        'product_name' => $item->first()['item']['name'],
//                        'status'       => $item->first()['item']['status'],
//                        'itemStock'    => $item->sum('quantity'),
//                    ];
                });
            });
        } else {
            info($this->items);
            $this->items = [];
        }

        if ($method == 'paginate') {
            return $this->paginate($this->items, $methodValue, null, URL::to('/') . '/api/admin/itemStock');
        }

        return $this->items;
//        } catch (Exception $exception) {
//            Log::info($exception->getMessage());
//            throw new Exception($exception->getMessage(), 422);
//        }
    }

    public function paginate(
        $items,
        $perPage = 15,
        $page = null,
        $baseUrl = null,
        $options = []
    )
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ?
            $items : Collection::make($items);

        $lap = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );

        if ($baseUrl) {
            $lap->setPath($baseUrl);
        }
        return $lap;
    }
}
