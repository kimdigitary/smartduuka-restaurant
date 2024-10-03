<?php

    namespace App\Http\Controllers\Admin;

    use App\Exports\PurchasesExport;
    use App\Http\Requests\PaginateRequest;
    use App\Http\Requests\PurchasePaymentRequest;
    use App\Http\Requests\PurchaseRequest;
    use App\Http\Requests\StorePosPaymentRequest;
    use App\Http\Resources\OrderResource;
    use App\Http\Resources\PaymentMethodResource;
    use App\Http\Resources\PosPaymentResource;
    use App\Http\Resources\PurchaseDetailsResource;
    use App\Http\Resources\PurchasePaymentResource;
    use App\Http\Resources\PurchaseResource;
    use App\Models\Order;
    use App\Models\PaymentMethod;
    use App\Models\ProductVariation;
    use App\Models\Purchase;
    use App\Models\PurchasePayment;
    use App\Services\ProductVariationService;
    use App\Services\PurchaseService;
    use Exception;
    use Illuminate\Contracts\Routing\ResponseFactory;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
    use Illuminate\Http\Response;
    use Maatwebsite\Excel\Facades\Excel;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;

    class PurchaseController extends AdminController
    {
        public PurchaseService         $purchaseService;
        public ProductVariationService $productVariationService;

        public function __construct(PurchaseService $purchaseService , ProductVariationService $productVariationService)
        {
            parent::__construct();
            $this->purchaseService         = $purchaseService;
            $this->productVariationService = $productVariationService;
            $this->middleware([ 'permission:purchase' ])->only('export' , 'downloadAttachment');
            $this->middleware([ 'permission:purchase_create' ])->only('store');
            $this->middleware([ 'permission:purchase_edit' ])->only('edit' , 'update');
            $this->middleware([ 'permission:purchase_delete' ])->only('destroy');
            $this->middleware([ 'permission:purchase_show' ])->only('show');
        }

        public function index(PaginateRequest $request) : Application | Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return PurchaseResource::collection($this->purchaseService->list($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function indexIngredients(PaginateRequest $request) : Application | Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return PurchaseResource::collection($this->purchaseService->ingreidentList($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function store(PurchaseRequest $request) : Application | Response | PurchaseResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return new PurchaseResource($this->purchaseService->store($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function storeIngredient(PurchaseRequest $request) : Application | Response | PurchaseResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return new PurchaseResource($this->purchaseService->storeIngredient($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function storeStock(PurchaseRequest $request) : Application | Response | PurchaseResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                $stored = $this->purchaseService->storeStock($request);
                return new PurchaseResource($stored);
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function show(Purchase $purchase) : Application | Response | PurchaseDetailsResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return new PurchaseDetailsResource($this->purchaseService->show($purchase));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function showPos(Order $order)
        {
            try {
                return new PosPaymentResource($this->purchaseService->showPos($order));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function edit(Purchase $purchase) : Application | Response | PurchaseDetailsResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return new PurchaseDetailsResource($this->purchaseService->edit($purchase));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function update(PurchaseRequest $request , Purchase $purchase) : Application | Response | PurchaseResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return new PurchaseResource($this->purchaseService->update($request , $purchase));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function destroy(Purchase $purchase) : Application | Response | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                $this->purchaseService->destroy($purchase);
                return response('' , 202);
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function export(PaginateRequest $request) : Application | Response | BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return Excel::download(new PurchasesExport($this->purchaseService , $request) , 'Purchases.xlsx');
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function downloadAttachment(Purchase $purchase)
        {
            try {
                return $this->purchaseService->downloadAttachment($purchase);
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function payment(PurchasePaymentRequest $request , Purchase $purchase) : Application | Response | PurchaseResource | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return new PurchaseResource($this->purchaseService->payment($request , $purchase));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function pos(StorePosPaymentRequest $request , Order $order)
        {
            try {
                return new OrderResource($this->purchaseService->pos($request , $order));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function paymentHistory(PaginateRequest $request , Purchase $purchase) : Application | Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return PurchasePaymentResource::collection($this->purchaseService->paymentHistory($request , $purchase));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function paymentMethods()
        {
            return PaymentMethodResource::collection(PaymentMethod::all());
        }

        public function posPaymentHistory(PaginateRequest $request , Order $order) : Application | Response | AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                return PurchasePaymentResource::collection($this->purchaseService->posPaymentHistory($request , $order));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function paymentDownloadAttachment(PurchasePayment $purchasePayment)
        {
            try {
                return $this->purchaseService->paymentDownloadAttachment($purchasePayment);
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function paymentDestroy(Purchase $purchase , PurchasePayment $purchasePayment) : Application | Response | \Illuminate\Contracts\Foundation\Application | ResponseFactory
        {
            try {
                $this->purchaseService->paymentDestroy($purchase , $purchasePayment);
                return response('' , 202);
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }
    }
