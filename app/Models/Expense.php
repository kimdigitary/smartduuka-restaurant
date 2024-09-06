<?php

    namespace App\Models;

    use App\Tenancy\TenantModel;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Spatie\MediaLibrary\HasMedia;
    use Spatie\MediaLibrary\InteractsWithMedia;

    class Expense extends TenantModel implements HasMedia
    {
        use HasFactory, InteractsWithMedia;

        protected $fillable = ['name', 'amount', 'date', 'category', 'note', 'paymentMethod', 'referenceNo', 'attachment', 'recurs', 'isRecurring', 'user_id','repetitions', 'paid', 'paid_on', 'repeats_on', 'registerMediaConversionsUsingModelInstance'];

        public function category() : HasOne
        {
//            return $this -> hasOne(ExpenseCategory::class, 'category', 'id');
            return $this -> hasOne(ExpenseCategory::class, 'id', 'category');
        }

        public function getAttachmentAttribute($value)
        {
            return asset('storage/' . $value);
//            if (!empty($this->getFirstMediaUrl('attachment'))) {
//                $product = $this->getMedia('attachment')->first();
//                return $product->getUrl();
//            }
        }
    }