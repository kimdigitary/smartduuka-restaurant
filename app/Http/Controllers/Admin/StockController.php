<?php

    namespace App\Http\Controllers\Admin;

    use App\Enums\Status;
    use App\Exports\StockExport;
    use App\Http\Requests\PaginateRequest;
    use App\Http\Requests\StoreIngredientStockRequest;
    use App\Http\Resources\StockResource;
    use App\Models\Ingredient;
    use App\Models\Item;
    use App\Models\Stock;
    use App\Services\StockService;
    use Exception;
    use Illuminate\Contracts\Routing\ResponseFactory;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
    use Illuminate\Http\Response;
    use Maatwebsite\Excel\Facades\Excel;

    class StockController extends AdminController
    {
        public StockService $stockService;

        public function __construct(StockService $stockService)
        {
            parent::__construct();
            $this->stockService = $stockService;
            $this->middleware([ 'permission:itemStock' ])->only('index' , 'export');
        }

        public function index(PaginateRequest $request) : Application | Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return StockResource::collection($this->stockService->list($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function indexIngredients(PaginateRequest $request)
        {
            try {
                return $this->stockService->listIngredients($request);
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function storeIngredientStock(StoreIngredientStockRequest $request)
        {
            try {
                foreach ( $request->products as $product ) {
                    $stock = Stock::where('model_type' , Ingredient::class)
                                  ->where('model_id' , $product['item_id'])
                                  ->first();
                    if ( $stock ) {
                        return $stock->increment('quantity' , $product['quantity']);
                    } else {
                        return Stock::create([
                            'model_type' => Ingredient::class ,
                            'model_id'   => $product['item_id'] ,
                            'item_type'  => Ingredient::class ,
                            'item_id'    => $product['item_id'] ,
                            'price'      => $product['buying_price'] ,
                            'quantity'   => $product['quantity'] ,
                            'discount'   => $product['total_discount'] ,
                            'tax'        => $product['total_tax'] ,
                            'subtotal'   => $product['subtotal'] ,
                            'total'      => $product['total'] ,
                            'status'     => Status::ACTIVE
                        ]);
                    }
                }
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function storeItemStock(StoreIngredientStockRequest $request)
        {
//            try {
                foreach ( $request->products as $product ) {
                    $stock = Stock::where('model_type' , Item::class)
                                  ->where('model_id' , $product['product_id'])
                                  ->first();
                    if ( $stock ) {
                        return $stock->increment('quantity' , $product['quantity']);
                    } else {
                        return Stock::create([
                            'model_type' => Item::class ,
                            'model_id'   => $product['product_id'] ,
                            'item_type'  => Item::class ,
                            'item_id'    => $product['product_id'] ,
                            'price'      => $product['price'] ,
                            'quantity'   => $product['quantity'] ,
                            'discount'   => $product['total_discount'] ,
                            'tax'        => $product['total_tax'] ,
                            'subtotal'   => $product['subtotal'] ,
                            'total'      => $product['total'] ,
                            'status'     => Status::ACTIVE
                        ]);
                    }
                }
//            } catch ( Exception $exception ) {
//                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
//            }
        }

        public function export(PaginateRequest $request) : Application | Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return Excel::download(new StockExport($this->stockService , $request) , 'Stock.xlsx');
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }
    }
