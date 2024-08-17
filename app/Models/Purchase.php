<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\morphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Purchase extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'supplier_id',
        'date',
        'reference_no',
        'subtotal',
        'tax',
        'discount',
        'payment_status',
        'balance',
        'total',
        'note',
        'status',
        'sku',
    ];

    protected $casts = [
        'supplier_id' => 'integer',
        'date' => 'datetime',
        'reference_no' => 'string',
        'subtotal' => 'decimal:6',
        'tax' => 'decimal:6',
        'discount' => 'decimal:6',
        'payment_status' => 'integer',
        'balance' => 'decimal:6',
        'total' => 'decimal:6',
        'note' => 'string',
        'status' => 'integer',
        'sku' => 'string',
    ];

    public function stocks(): morphMany
    {
        return $this->morphMany(Stock::class, 'model');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function getFileAttribute()
    {
        if (!empty($this->getFirstMediaUrl('purchase'))) {
            $product = $this->getMedia('purchase')->first();
            return $product->getUrl();
        }
    }

    public function purchasePayment(): HasMany
    {
        return $this->HasMany(PurchasePayment::class, 'purchase_id', 'id');
    }

    public function getBalanceAttribute()
    {
        $totalPayments = $this->purchasePayment()->sum('amount');
        return $this->total - $totalPayments;
    }

    public static function scopeWithTotalBalance($query)
    {
        return $query->get()->sum(function($purchase) {
            return $purchase->balance;
        });
    }
}
