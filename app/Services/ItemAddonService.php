<?php

namespace App\Services;


use App\Http\Requests\ItemAddonRequest;
use App\Http\Requests\ItemIngredientRequest;
use App\Http\Requests\PaginateRequest;
use App\Models\Item;
use App\Models\ItemAddon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemAddonService
{
    public $itemExtra;
    protected $itemExtraFilter = [
        'item_id',
        'name',
        'price',
        'status'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, Item $item)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return ItemAddon::with('item', 'addonItem')->where(['item_id' => $item->id])->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemExtraFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(ItemAddonRequest $request, Item $item)
    {
        try {
            return ItemAddon::create($request->validated() + ['item_id' => $item->id]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function storeIngredient(ItemIngredientRequest $request, Item $item)
    {
        try {
            DB::transaction(function () use ($request, $item) {
                $syncData = [];
                foreach (json_decode($request->ingredients, true) as $ingredient) {
                    $syncData[$ingredient['ingredient_id']] = [
                        'quantity'     => $ingredient['quantity'],
                        'buying_price' => $ingredient['buying_price'],
                        'total'        => $ingredient['total'],
                    ];
                }
                $item->ingredients()->syncWithoutDetaching($syncData);
                return $item->ingredients->last();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Item $item, ItemAddon $itemExtra)
    {
        try {
            if ($item->id == $itemExtra->item_id) {
                $itemExtra->delete();
            } else {
                throw new Exception(trans('all.item_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
