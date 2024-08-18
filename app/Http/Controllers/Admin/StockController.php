<?php

namespace App\Http\Controllers\Admin;

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

    public function export(PaginateRequest $request): Application|Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try {
            return Excel::download(new StockExport($this->stockService, $request), 'Stock.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
