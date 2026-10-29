<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'user_id',
        'sale_number',
        'total',
        'status',
        'notes',
        'sold_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'sold_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // Alias for convenience
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateSaleNumber(): string
    {
        $prefix = 'SAL-' . date('Ymd');
        $lastSale = static::withTrashed()
            ->where('sale_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSale) {
            $parts = explode('-', $lastSale->sale_number);
            $lastNumber = isset($parts[2]) ? (int) $parts[2] : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getTotalAmountAttribute()
    {
        return $this->total;
    }
}
