<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardPoint extends Model
{
    use HasFactory;

    // Transaction types
    const TYPE_EARNED = 'earned';
    const TYPE_REDEEMED = 'redeemed';
    const TYPE_EXPIRED = 'expired';
    const TYPE_ADJUSTED = 'adjusted';

    protected $fillable = [
        'user_id', 
        'invoice_id', 
        'points', 
        'transaction_type', 
        'description', 
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the total available points for a user
     */
    public static function getAvailablePoints($userId)
    {
        $earned = self::where('user_id', $userId)
            ->where('transaction_type', self::TYPE_EARNED)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->sum('points');

        $spent = self::where('user_id', $userId)
            ->whereIn('transaction_type', [self::TYPE_REDEEMED, self::TYPE_EXPIRED])
            ->sum('points');

        return max(0, $earned - abs($spent));
    }

    /**
     * Get points breakdown for a user
     */
    public static function getPointsBreakdown($userId)
    {
        $settings = Setting::first();
        $ratio = $settings->points_to_currency_ratio ?? 1;

        $total_earned = self::where('user_id', $userId)
                ->where('transaction_type', self::TYPE_EARNED)
                ->sum('points');

        $total_redeemed = abs(self::where('user_id', $userId)
                ->where('transaction_type', self::TYPE_REDEEMED)
                ->sum('points'));

        $total_expired = abs(self::where('user_id', $userId)
                ->where('transaction_type', self::TYPE_EXPIRED)
                ->sum('points'));

        return [
            'total_earned' => $total_earned,
            'total_redeemed' => $total_redeemed,
            'total_expired' => $total_expired,
            'available' => $available = self::getAvailablePoints($userId),
            'total_earned_currency' => $total_earned * $ratio,
            'total_redeemed_currency' => $total_redeemed * $ratio,
            'total_expired_currency' => $total_expired * $ratio,
            'available_currency' => $available * $ratio,
        ];
    }
}
