<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\PaginateRequest;
    use App\Http\Requests\StorePaymentMethodRequest;
    use App\Http\Requests\UpdatePaymentMethodRequest;
    use App\Http\Resources\PaymentMethodResource;
    use App\Models\PaymentMethod;
    use Exception;
    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Contracts\Routing\ResponseFactory;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
    use Illuminate\Http\Response;

    class PaymentMethodController extends Controller
    {
        public function index(PaginateRequest $request , PaymentMethod $paymentMethod) : Response | AnonymousResourceCollection | Application | ResponseFactory
        {
            try {
                $method      = $request->get('paginate' , 0) == 1 ? 'paginate' : 'get';
                $methodValue = $request->get('paginate' , 0) == 1 ? $request->get('per_page' , 10) : '*';
                $orderColumn = $request->get('order_column') ?? 'id';
                $orderType   = $request->get('order_type') ?? 'desc';

                $paymentMethods = PaymentMethod::orderBy($orderColumn , $orderType)->$method(
                    $methodValue
                );
                return PaymentMethodResource::collection($paymentMethods);

            } catch ( Exception $exception ) {
                return response([ 'status' => false , 'message' => $exception->getMessage() ] , 422);
            }
        }

        public function store(StorePaymentMethodRequest $request)
        {
            PaymentMethod::create($request->validated());
            return PaymentMethodResource::collection(PaymentMethod::all());
        }


        public function update(UpdatePaymentMethodRequest $request , PaymentMethod $method)
        {
            $method->update($request->validated());
            return PaymentMethodResource::collection(PaymentMethod::all());
        }

        public function destroy(PaymentMethod $method)
        {
            $method->delete();
            return PaymentMethodResource::collection(PaymentMethod::all());
        }
    }
