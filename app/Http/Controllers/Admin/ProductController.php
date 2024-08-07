<?php

    namespace App\Http\Controllers\Admin;

    use App\Exports\ProductExport;
    use App\Http\Requests\ChangeImageRequest;
    use App\Http\Requests\PaginateRequest;
    use App\Http\Requests\ProductOfferRequest;
    use App\Http\Requests\ProductRequest;
    use App\Http\Requests\ShippingAndReturnRequest;
    use App\Http\Resources\ItemResource;
    use App\Http\Resources\ProductAdminResource;
    use App\Http\Resources\ProductDetailsAdminResource;
    use App\Http\Resources\SimpleProductDetailsResource;
    use App\Http\Resources\simpleProductWithVariationCountResource;
    use App\Models\Product;
    use App\Services\ProductService;
    use Exception;
    use Illuminate\Contracts\Routing\ResponseFactory;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
    use Illuminate\Http\Response;
    use Maatwebsite\Excel\Facades\Excel;

    class ProductController extends AdminController
    {
        public ProductService $productService;

        public function __construct(ProductService $productService)
        {
            parent ::__construct();
            $this -> productService = $productService;
            $this -> middleware(['permission:products']) -> only('export', 'generateSku');
            $this -> middleware(['permission:products_create']) -> only('store', 'uploadImage');
            $this -> middleware(['permission:products_edit']) -> only('update');
            $this -> middleware(['permission:products_delete']) -> only('destroy', 'deleteImage');
            $this -> middleware(['permission:products_show']) -> only('show');
        }


        public function index(PaginateRequest $request
        ) : Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return ProductAdminResource ::collection($this -> productService -> list($request));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function show(Product $product
        ) : Application | Response | ProductDetailsAdminResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return new ProductDetailsAdminResource($this -> productService -> show($product));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function store(ProductRequest $request
        ) : Response | ProductAdminResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return new ProductAdminResource($this -> productService -> store($request));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function update(
            ProductRequest $request,
            Product $product
        ) : Response | ProductAdminResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return new ProductAdminResource($this -> productService -> update($request, $product));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function destroy(Product $product) : Response | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                $this -> productService -> destroy($product);
                return response('', 202);
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function uploadImage(
            ChangeImageRequest $request,
            Product $product
        ) : Application | Response | ProductDetailsAdminResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return new ProductDetailsAdminResource($this -> productService -> uploadImage($request, $product));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function deleteImage(
            Product $product,
            $index
        ) : Application | Response | ProductDetailsAdminResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return new ProductDetailsAdminResource($this -> productService -> deleteImage($product, $index));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function export(PaginateRequest $request
        ) : Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return Excel ::download(new ProductExport($this -> productService, $request), 'Product.xlsx');
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function generateSku(
        ) : Application | Response | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return response(['data' => ['product_sku' => $this -> productService -> generateSku()]], 200);
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function productOffer(
            ProductOfferRequest $request,
            Product $product
        ) : Application | Response | ProductAdminResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return new ProductAdminResource($this -> productService -> productOffer($request, $product));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function purchasableProducts() : Application | Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return simpleProductWithVariationCountResource ::collection($this -> productService -> purchasableProducts());
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }


        public function simpleProducts(
        ) : Application | Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return simpleProductWithVariationCountResource ::collection($this -> productService -> simpleProducts());
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }

        public function posProduct(
            Product $product,
            Request $request
        ) : SimpleProductDetailsResource | Application | Response | \Illuminate\Contracts\Foundation\Application | ResponseFactory {
            try {
                return new SimpleProductDetailsResource($this -> productService -> showWithRelation($product, $request));
            } catch (Exception $exception) {
                return response(['status' => false, 'message' => $exception -> getMessage()], 422);
            }
        }
    }
