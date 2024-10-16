<?php

    namespace App\Http\Resources;


    use App\Models\DiningTable;
    use Illuminate\Http\Resources\Json\JsonResource;

    class SimpleUserResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param \Illuminate\Http\Request $request
         * @return array
         */
        public function toArray($request) : array
        {
            return [
                "id"            => $this->id ,
                "name"          => $this->name ,
            ];
        }
    }
