<?php

    namespace App\Http\Controllers;

    use App\Models\Expense;
    use App\Traits\ApiResponse;
    use App\Traits\AuthUser;
    use App\Traits\FilesTrait;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;

    class ExpensesController extends Controller
    {
        use ApiResponse, FilesTrait, AuthUser;

        public function index(Request $request)
        {
            $name = $request -> name;
//            $from_date = date('Y-m-d', strtotime($request -> from_date));
            $from_date = $request -> from_date;
//            $to_date   = date('Y-m-d', strtotime($request -> to_date));
            $to_date = $request -> to_date;
            $data    = Expense ::when($name, function ($query) use ($name) {
                $query -> where('name', 'like', '%' . $name . '%');
            }) -> when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query -> whereBetween('created_at', [Carbon ::parse($from_date) -> copy() -> startOfDay(), Carbon ::parse($to_date) -> copy() -> endOfDay()]);
            }) -> with('category') -> where('user_id', $this -> id()) -> get();
            return $this -> response(true, 'success', data : $data);
        }

        public function store(Request $request)
        {
            $validator = Validator ::make(
                $request -> all(),
                [
                    'name'          => 'required',
                    'amount'        => 'required',
                    'date'          => 'required',
                    'category'      => 'required',
                    'paymentMethod' => 'required',
                    'referenceNo'   => 'required_if:paymentMethod,2|required_if:paymentMethod,3|required_if:paymentMethod,4',
                    'recurs'        => 'required_if:isRecurring,true',
                    'repeatsOn'     => 'required_if:isRecurring,true',
                    'paymentAmount' => 'sometimes|integer',
                    'paidOn'        => 'sometimes|date',
                ]
            );
            $validator->setAttributeNames([
                'name'          => 'name',
                'amount'        => 'amount',
                'date'          => 'date',
                'category'      => 'category',
                'paymentMethod' => 'paymentMethod',
                'referenceNo'   => 'referenceNo',
                'recurs'        => 'recurs',
                'repeatsOn'     => 'repeatsOn',
                'paymentAmount' => 'paymentAmount',
                'paidOn'        => 'paidOn',
            ]);
            if ($validator -> fails()) {
                return $this -> response(message : $validator -> errors() -> first(), data : [$validator -> errors() -> keys()[ 0 ] => $validator -> errors() -> first()]);
            }
            try {
                DB ::beginTransaction();
                $expense = Expense ::create([
                    'name'          => $request -> name,
                    'amount'        => $request -> amount,
                    'date'          => date('Y-m-d H:i:s', strtotime($request -> date)),
                    'category'      => $request -> category,
                    'paymentMethod' => $request -> paymentMethod,
                    'referenceNo'   => $request -> referenceNo,
                    'isRecurring'   => $request -> isRecurring ?? 0,
                    'recurs'        => $request -> recurs,
                    'user_id'       => $this -> id(),
                    'repetitions'   => $request -> repetitions ?? 0,
                    'repeats_on'    => $request -> repeatsOn ? date('Y-m-d H:i:s', strtotime($request -> repeatsOn)) : null,
                    'paid'          => $request -> paymentAmount ?? 0,
                    'paid_on'       => $request -> paidOn ? date('Y-m-d H:i:s', strtotime($request -> paidOn)) : null,
                ]);
                $this -> saveFiles($request, $expense, ['attachment' => 'attachment']);
                if ($expense) {
                    DB ::commit();
                    return $this -> response(true, message : 'success', data : $expense);
                } else {
                    DB ::rollBack();
                    return $this -> response(message : 'Expense creation failed');
                }
            } catch (Exception $exception) {
                info($exception -> getMessage());
                DB ::rollBack();
                return $this -> response(message : $exception -> getMessage());
            }
        }

        public function show(string $id)
        {
            $expense = Expense ::with('category') -> where('user_id', $this -> id()) -> find($id);
            return $this -> response(true, 'success', data : $expense);
        }

        public function update(Request $request, string $id)
        {
            //
        }

        public function destroy(string $id)
        {
            //
        }
    }
