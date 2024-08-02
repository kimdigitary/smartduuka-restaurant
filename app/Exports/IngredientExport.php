<?php

namespace App\Exports;

use App\Http\Requests\PaginateRequest;
use App\Services\IngredientsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IngredientExport implements FromCollection, WithHeadings
{

    public IngredientsService $ingredientsService;
    public PaginateRequest $request;

    public function __construct(IngredientsService $ingredientsService, $request)
    {
        $this->ingredientsService = $ingredientsService;
        $this->request            = $request;
    }

    public function collection(): Collection
    {
        $itemArray = [];
        $items     = $this->ingredientsService->list($this->request);

        foreach ($items as $item) {
            $itemArray[] = [
                $item->name,
                $item->buying_price,
                $item->unit,
                $item->quantity,
                $item->quantity_alert,
            ];
        }
        return collect($itemArray);
    }

    public function headings(): array
    {
        return [
            'Name','Buying Price','Unit','Quantity','Quantity Alert'
        ];
    }
}
