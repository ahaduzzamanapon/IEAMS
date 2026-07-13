<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertySale extends Model
{
    protected $fillable = [
        'property_type', 'plot_id', 'apartment_id', 'sale_date', 'buyer_name', 'father_name', 'mother_name',
        'nid', 'passport', 'mobile', 'email', 'address', 'sale_value',
        'registration_number', 'registration_date', 'deed_number', 'payment_status', 'handover_date'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'registration_date' => 'date',
        'handover_date' => 'date',
        'sale_value' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (PropertySale $sale) {
            if ($sale->property_type === 'plot') {
                $plot = $sale->plot;
                if ($plot && $plot->status !== 'vacant') {
                    throw new \InvalidArgumentException("Plot status must be 'vacant' to make a sale.");
                }
            } else {
                $apt = $sale->apartment;
                if ($apt && $apt->status !== 'vacant') {
                    throw new \InvalidArgumentException("Apartment status must be 'vacant' to make a sale.");
                }
                
                if ($apt && $apt->floor && $apt->floor->building && $apt->floor->building->construction_status === 'under_construction') {
                    throw new \InvalidArgumentException("Cannot sell an apartment in a building under construction.");
                }
            }

            if ($sale->sale_value <= 0) {
                throw new \InvalidArgumentException("Sale value must be greater than zero.");
            }
        });

        static::created(function (PropertySale $sale) {
            if ($sale->property_type === 'plot') {
                $sale->plot->update(['status' => 'sold']);
            } else {
                $sale->apartment->update(['status' => 'sold']);
            }
        });
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }
}
