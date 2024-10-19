<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\MorphTo;
    use Razorpay\Api\Product;
    use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

    class Stock extends Model
    {
        use HasFactory;
        use HasRecursiveRelationships;

        protected $table    = "stocks";
        protected $fillable = [
            'product_id' ,
            'item_id' ,
            'model_type' ,
            'model_id' ,
            'item_type' ,
            'variation_names' ,
            'price' ,
            'quantity' ,
            'discount' ,
            'tax' ,
            'sku' ,
            'status' ,
            'subtotal' ,
            'total' ,
            'type'
        ];

        protected $casts = [
            'item_id'    => 'integer' ,
            'model_type' => 'string' ,
            'model_id'   => 'integer' ,
            'item_type'  => 'string' ,
            'price'      => 'integer' ,
            'quantity'   => 'decimal:2' ,
            'discount'   => 'decimal:6' ,
            'tax'        => 'decimal:6' ,
            'status'     => 'integer' ,
            'subtotal'   => 'decimal:6' ,
            'total'      => 'decimal:6' ,
        ];

        public function item() : MorphTo
        {
            return $this->morphTo();
        }

        public function ingredient()
        {
//        return $this->morphTo();
            return $this->hasOne(Ingredient::class , 'id' , 'item_id');
        }

//    public function item(): BelongsTo
//    {
//        return $this->belongsTo(Item::class)->withTrashed();
//    }
        public function product() : BelongsTo
        {
            return $this->belongsTo(Product::class)->withTrashed();
        }

        public function productTax() : BelongsTo
        {
            return $this->belongsTo(Tax::class , 'tax_id');
        }

        public function stockTaxes() : HasMany
        {
            return $this->hasMany(StockTax::class);
        }
    }
