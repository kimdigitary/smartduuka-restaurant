<?php

    namespace App\Services;

    use App\Enums\PaymentStatus;
    use App\Enums\Role as EnumRole;
    use App\Libraries\AppLibrary;
    use App\Models\Expense;
    use App\Models\Item;
    use App\Models\Order;
    use App\Models\PurchasePayment;
    use App\Models\User;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class DashboardService
    {

        public function salesSummary(Request $request)
        {
            $order = new Order;
            if ( $request->first_date && $request->last_date ) {
                $first_date = Date('Y-m-d' , strtotime($request->first_date));
                $last_date  = Date('Y-m-d' , strtotime($request->last_date));
            } else {
                $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
            }

            $date      = date_diff(date_create($first_date) , date_create($last_date) , false);
            $date_diff = (int) $date->format("%a");

            $total_sales = AppLibrary::flatAmountFormat($order->whereDate('order_datetime' , '>=' , $first_date)->whereDate('order_datetime' , '<=' , $last_date)->where('payment_status' , PaymentStatus::PAID)->sum('total'));

            $dateRangeArray = [];
            for ( $currentDate = strtotime($first_date) ; $currentDate <= strtotime($last_date) ; $currentDate += ( 86400 ) ) {

                $date             = date('Y-m-d' , $currentDate);
                $dateRangeArray[] = $date;
            }

            $dateRangeValueArray = [];
            for ( $i = 0 ; $i <= count($dateRangeArray) - 1 ; $i++ ) {
                $per_day               = AppLibrary::flatAmountFormat($order->whereDate('order_datetime' , $dateRangeArray[$i])->where('payment_status' , PaymentStatus::PAID)->sum('total'));
                $dateRangeValueArray[] = floatval($per_day);
            }


            $salesSummaryArray = [];
            if ( $date_diff > 0 ) {
                $salesSummaryArray['total_sales']   = AppLibrary::currencyAmountFormat($total_sales);
                $salesSummaryArray['avg_per_day']   = AppLibrary::currencyAmountFormat($total_sales / $date_diff);
                $salesSummaryArray['per_day_sales'] = $dateRangeValueArray;
            } else {
                $salesSummaryArray['total_sales']   = AppLibrary::currencyAmountFormat($total_sales);
                $salesSummaryArray['avg_per_day']   = AppLibrary::currencyAmountFormat($total_sales);
                $salesSummaryArray['per_day_sales'] = $dateRangeValueArray;
            }

            return $salesSummaryArray;
        }

        public function customerStates(Request $request)
        {
            $order = new Order;
            if ( $request->first_date && $request->last_date ) {
                $first_date = Date('Y-m-d' , strtotime($request->first_date));
                $last_date  = Date('Y-m-d' , strtotime($request->last_date));
            } else {
                $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
            }

            $timeArray = [ "06:00" , "07:00" , "08:00" , "09:00" , "10:00" , "11:00" , "12:00" , "13:00" , "14:00" , "15:00" , "16:00" , "17:00" , "18:00" , "19:00" , "20:00" , "21:00" , "22:00" , "23:00" ];

            $customerSateArray  = [];
            $totalCustomerArray = [];
            $first_time         = "";
            $last_time          = "";
            for ( $i = 0 ; $i <= count($timeArray) - 1 ; $i++ ) {
                $first_time = date('H:i' , strtotime($timeArray[$i]));
                $last_time  = date('H:i' , strtotime($timeArray[$i] . ' +59 minutes'));

                $total_customer       = $order->whereDate('order_datetime' , '>=' , $first_date)->whereDate('order_datetime' , '<=' , $last_date)->whereTime('order_datetime' , '>=' , Carbon::parse($first_time))->whereTime('order_datetime' , '<=' , Carbon::parse($last_time))->get()->count();
                $totalCustomerArray[] = $total_customer;
            }

            $customerSateArray['total_customers'] = $totalCustomerArray;
            $customerSateArray['times']           = $timeArray;

            return $customerSateArray;
        }

        public function totalSales(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }
                return Order::where('payment_status' , PaymentStatus::PAID)->whereDate('order_datetime' , '>=' , $first_date)->whereDate('order_datetime' , '<=' , $last_date)->sum('total');

            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function totalOrders(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }

                return Order::whereDate('order_datetime' , '>=' , $first_date)
//                            -> where('status' ,'<>', OrderStatus::CANCELED)
                            ->whereDate('order_datetime' , '<=' , $last_date)
                            ->count();

            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function totalExpenses(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }
                $expenses = Expense::whereDate('date' , '>=' , $first_date)
                                   ->whereDate('date' , '<=' , $last_date)->sum('paid');

                $purchases = PurchasePayment::whereDate('date' , '>=' , $first_date)
                                            ->whereDate('date' , '<=' , $last_date)
                                            ->sum('amount');
                return $expenses + $purchases;

            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function totalPendingExpenses(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }
                // sum difference paid and amount in Expenses

                return Expense::whereDate('date' , '>=' , $first_date)
                              ->whereDate('date' , '<=' , $last_date)
                              ->sum(DB::raw('amount - paid'));

            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function totalPurchases(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }

                return PurchasePayment::whereDate('date' , '>=' , $first_date)
                                      ->whereDate('date' , '<=' , $last_date)
                                      ->sum('amount');

            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function totalProfits(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }
                $expenses = Expense::whereDate('date' , '>=' , $first_date)
                                   ->whereDate('date' , '<=' , $last_date)->sum('amount');

                $purchases = PurchasePayment::whereDate('date' , '>=' , $first_date)
                                            ->whereDate('date' , '<=' , $last_date)
                                            ->sum('amount');
                return $expenses + $purchases;

            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function totalCustomers(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }
//                return User::role(EnumRole::CUSTOMER)->whereDate('created_at' , '>=' , $first_date)->whereDate('created_at' , '<=' , $last_date)->count();
                return User::role(EnumRole::CUSTOMER)->count();
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }

        public function totalMenuItems(Request $request)
        {
            try {
                if ( $request->first_date && $request->last_date ) {
                    $first_date = Date('Y-m-d' , strtotime($request->first_date));
                    $last_date  = Date('Y-m-d' , strtotime($request->last_date));
                } else {
                    $first_date = Date('Y-m-01' , strtotime(Carbon::today()->toDateString()));
                    $last_date  = Date('Y-m-t' , strtotime(Carbon::today()->toDateString()));
                }
//                return Item::whereDate('created_at' , '>=' , $first_date)->whereDate('created_at' , '<=' , $last_date)->count();
                return Item::count();
            } catch ( Exception $exception ) {
                Log::info($exception->getMessage());
                throw new Exception($exception->getMessage() , 422);
            }
        }
    }
