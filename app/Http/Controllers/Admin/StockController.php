<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PurchaseStatus;
use App\Enums\Status;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\StoreIngredientStockRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Stock;
use Exception;
use App\Exports\StockExport;
use App\Services\StockService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\StockResource;
use App\Http\Requests\PaginateRequest;

class StockController extends AdminController
{
    public StockService $stockService;

    public function __construct(StockService $stockService)
    {
        parent::__construct();
        $this->stockService = $stockService;
        $this->middleware(['permission:itemStock'])->only('index', 'export');
    }

    public function index(PaginateRequest $request): Application|Response|AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            return StockResource::collection($this->stockService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function indexIngredients(PaginateRequest $request): Application|Response|AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            return StockResource::collection($this->stockService->listIngredients($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function storeIngredientStock(StoreIngredientStockRequest $request):
    Application|Response|PurchaseResource|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            $stock = Stock::create([
                'model_type' => Purchase::class,
                'model_id'   => 1,
                'item_type'  => Ingredient::class,
                'item_id'    => $product['product_id'],
                'price'      => $product['price'],
                'quantity'   => $product['quantity'],
                'discount'   => $product['total_discount'],
                'tax'        => $product['total_tax'],
                'subtotal'   => $product['subtotal'],
                'total'      => $product['total'],
                'status'     => $request->status == PurchaseStatus::RECEIVED ? Status::ACTIVE : Status::INACTIVE
            ]);
            return new PurchaseResource($stored);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request): Application|Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            return Excel::download(new StockExport($this->stockService, $request), 'Stock.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
