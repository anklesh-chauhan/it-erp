<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'subtotal',
        'tax',
        'total',
        'currency',
        'payment_terms',
        'payment_method',
        'sales_person_id',
        'description',
        'rejected_at',
        'canceled_at',
        'sent_at',
        'shipping_method',
        'shipping_cost',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $fillable = self::FILLABLE;

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
        return $this->belongsTo(Company::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(\App\Models\Address::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(\App\Models\Address::class, 'shipping_address_id');
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
