<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Str;

class Sale extends BaseModel
{
    use HasFactory;

    protected $fillable = ['total', 'items', 'status', 'type', 'code', 'client_id', 'user_id', 'currency_id'];

    protected static function booted()
    {
        static::creating(function (Sale $sale) {
            $sale->code = Str::random(8);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function products()
    {
        return $this->hasMany(SaleDetails::class, 'sale_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalProducts()
    {
        return $this->products->sum('quantity');
    }

    public function getTotalPayed()
    {
        $result = \DB::table('payments')
            ->join('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where('payments.sale_id', $this->id)
            ->selectRaw('
                COALESCE(SUM(payments.cash_usd - payments.change_usd), 0) as total_usd,
                COALESCE(SUM(payments.cash_bs - payments.change_bs), 0) as total_bs,
                COALESCE(SUM(
                    (payments.cash_usd - payments.change_usd) + 
                    ((payments.cash_bs - payments.change_bs) / currencies.value)
                ), 0) as total_usd_equivalent
            ')
            ->first();

        return [
            'total_usd' => (float) max(0, $result->total_usd),
            'total_bs' => (float) max(0, $result->total_bs),
            'total_usd_equivalent' => (float) max(0, $result->total_usd_equivalent)
        ];
    }

    /**
     * Get total change in USD equivalent (sum of all change given in payments)
     */
    public function getTotalChangeUSD()
    {
        $result = \DB::table('payments')
            ->join('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where('payments.sale_id', $this->id)
            ->selectRaw('
                COALESCE(SUM(payments.change_usd), 0) as change_usd,
                COALESCE(SUM(payments.change_bs), 0) as change_bs,
                COALESCE(SUM(
                    payments.change_usd + 
                    (payments.change_bs / currencies.value)
                ), 0) as total_change_usd_equivalent
            ')
            ->first();

        return (float) max(0, $result->total_change_usd_equivalent);
    }

    public function getRemainingAmount()
    {
        $totalPayed = $this->getTotalPayed();
        return max(0, $this->total - $totalPayed['total_usd_equivalent']);
    }

    public function isFullyPaid()
    {
        return $this->getRemainingAmount() <= 0.01;
    }

    // New method to get payment summary
    public function getPaymentSummary()
    {
        $totalPayed = $this->getTotalPayed();
        $remaining = $this->getRemainingAmount();

        return [
            'total_sale' => $this->total,
            'total_payed_usd' => $totalPayed['total_usd'],
            'total_payed_bs' => $totalPayed['total_bs'],
            'total_payed_usd_equivalent' => $totalPayed['total_usd_equivalent'],
            'remaining_amount' => $remaining,
            'is_fully_paid' => $this->isFullyPaid()
        ];
    }
}