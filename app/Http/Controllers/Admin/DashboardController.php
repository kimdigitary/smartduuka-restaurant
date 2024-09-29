<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Resources\CustomerStatesResource;
    use App\Http\Resources\ItemResource;
    use App\Http\Resources\SalesSummaryResource;
    use App\Libraries\AppLibrary;
    use App\Services\DashboardService;
    use App\Services\ItemService;
    use Exception;
    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Contracts\Routing\ResponseFactory;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class DashboardController extends AdminController
    {
        private DashboardService $dashboardService;
        private ItemService      $itemService;

        public function __construct(DashboardService $dashboardService , ItemService $itemService)
        {
            parent::__construct();
            $this->dashboardService = $dashboardService;
            $this->itemService      = $itemService;
            $this->middleware([ 'permission:dashboard' ])->only(
                'orderStatistics' ,
                'orderSummary' ,
                'featuredItems' ,
                'mostPopularItems' ,
                'topCustomers' ,
                'totalSales' ,
                'salesSummary' ,
                'customerStates' ,
                'totalOrders' ,
                'totalCustomers' ,
                'totalMenuItems'
            );
        }

        public function totalSales(Request $request) : Response | array | Application | ResponseFactory
        {
            try {
                return [ 'data' => [ 'total_sales' => AppLibrary::currencyAmountFormat($this->dashboardService->totalSales($request)) ] ];
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function totalOrders(Request $request) : Response | array | Application | ResponseFactory
        {
            try {
                return [ 'data' => [ 'total_orders' => number_format($this->dashboardService->totalOrders($request)) ] ];
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function totalExpenses(Request $request) : Response | array | Application | ResponseFactory
        {
            try {
//            return ['data' => ['total_expenses' => $this->dashboardService->totalExpenses($request)]];
                return [ 'data' => [ 'total_expenses' => AppLibrary::currencyAmountFormat($this->dashboardService->totalExpenses($request)) ] ];
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function totalPendingExpenses(Request $request) : Response | array | Application | ResponseFactory
        {
            try {
//            return ['data' => ['total_pending_expenses' => $this->dashboardService->totalPendingExpenses($request)]];
                return [ 'data' => [ 'total_pending_expenses' => AppLibrary::currencyAmountFormat($this->dashboardService->totalPendingExpenses($request)) ] ];
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function totalProfits(Request $request) : Response | array | Application | ResponseFactory
        {
            try {
//            return ['data' => ['total_profits' => $this->dashboardService->totalProfits($request)]];
                return [ 'data' => [ 'total_profits' => AppLibrary::currencyAmountFormat(
                    $this->dashboardService->totalSales($request) -
                    ( $this->dashboardService->totalExpenses($request) + $this->dashboardService->totalPurchases($request) ))
                ]
                ];
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function totalCustomers(Request $request) : Response | array | Application | ResponseFactory
        {
            try {
                return [ 'data' => [ 'total_customers' => $this->dashboardService->totalCustomers($request) ] ];
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function totalMenuItems(Request $request) : Response | array | Application | ResponseFactory
        {
            try {
                return [ 'data' => [ 'total_menu_items' => $this->dashboardService->totalMenuItems($request) ] ];
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function salesSummary(
            Request $request
        ) : Response | SalesSummaryResource | Application | ResponseFactory {
            try {
                return new SalesSummaryResource($this->dashboardService->salesSummary($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function customerStates(
            Request $request
        ) : Response | CustomerStatesResource | Application | ResponseFactory {
            try {
                return new CustomerStatesResource($this->dashboardService->customerStates($request));
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function featuredItems() : Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | Application | ResponseFactory
        {
            try {
                return ItemResource::collection($this->itemService->featuredItems());
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function mostPopularItems() : Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | Application | ResponseFactory
        {
            try {
                return ItemResource::collection($this->itemService->mostPopularItems());
            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }
    }
