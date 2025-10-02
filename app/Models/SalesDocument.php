<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphOne;

abstract class SalesDocument extends Model
{
    public const FILLABLE = [
        'document_number',
        'lead_id',
        'contact_detail_id',
        'billing_address_id',
        'shipping_address_id',
        'date',
        'status',
        'discount_mode',
        'gross_total',
        'subtotal',
        'discount_type',
        'discount_value',
        'transaction_discount',
        'tax',
        'round_off',
        'total',
        'currency',
        'payment_term_id',
        'payment_method_id',
        'sales_person_id',
        'description',
        'rejected_at',
        'canceled_at',
        'sent_at',
        'shipping_method_id',
        'shipping_cost',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $fillable = self::FILLABLE;

    public function termsAndCondition(): MorphOne
    {
        return $this->morphOne(TermsAndCondition::class, 'model');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }   

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function contactDetail()
    {
        return $this->belongsTo(ContactDetail::class);
    }

    public function company()
    {
        return $this->belongsTo(AccountMaster::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function items()
    {
        return $this->morphMany(SalesDocumentItem::class, 'document');
    }

    public function taxDetails()
    {
        return $this->morphMany(TaxDetail::class, 'taxable');
    }


    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum(fn ($item) => $item->quantity * $item->price);
        $this->tax = $this->subtotal * 0.1; // Example: 10% tax, adjust as needed
        $this->total = $this->subtotal + $this->tax;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id(); // Set the user who updated the record
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id(); // Set the user who deleted the record
        });

        static::created(function ($model) {
            NumberSeries::incrementNextNumber($model::class);
        });
    }
}
