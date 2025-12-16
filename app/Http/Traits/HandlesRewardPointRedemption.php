<?php

namespace App\Http\Traits;

use App\Models\RewardPoint;
use App\Models\Setting;

trait HandlesRewardPointRedemption
{
    /**
     * Process reward points redemption for an invoice
     *
     * @param int $userId
     * @param int $invoiceId
     * @param float $invoiceAmount
     * @param int|null $pointsToRedeem
     * @param float|null $amountPaid Optional - if provided, ensures total doesn't exceed invoice
     * @return array ['points_redeemed' => int, 'discount_amount' => float, 'final_amount' => float, 'error' => string|null]
     */
    protected function processPointsRedemption($userId, $invoiceId, $invoiceAmount, $pointsToRedeem = null, $amountPaid = null)
    {
        $result = [
            'points_redeemed' => 0,
            'discount_amount' => 0,
            'final_amount' => $invoiceAmount,
            'error' => null
        ];

        // If no points to redeem, return original amount
        if (empty($pointsToRedeem) || $pointsToRedeem <= 0) {
            return $result;
        }

        $settings = Setting::first();
        $minPointsRedeemable = $settings->min_points_redeemable ?? 100;
        $maxPercentage = $settings->max_invoice_percentage_payable_by_points ?? 100;
        $pointsRatio = $settings->points_to_currency_ratio ?? 1.00;

        // If amount paid is provided, auto-adjust points to only use what's needed
        if ($amountPaid !== null && $amountPaid > 0) {
            $remainingBalance = $invoiceAmount - $amountPaid;
            
            // If amount paid already covers invoice, no points needed
            if ($remainingBalance <= 0) {
                $result['error'] = "Amount paid (₦" . number_format($amountPaid, 2) . ") already covers the invoice. No points redemption needed.";
                return $result;
            }
            
            // Auto-adjust points to only cover the remaining balance
            $maxPointsNeeded = ceil($remainingBalance / $pointsRatio);
            if ($pointsToRedeem > $maxPointsNeeded) {
                $pointsToRedeem = $maxPointsNeeded;
            }
        }

        // Validation: minimum points check
        if ($pointsToRedeem < $minPointsRedeemable) {
            $result['error'] = "Minimum redeemable points is {$minPointsRedeemable}";
            return $result;
        }

        // Get available points
        $availablePoints = RewardPoint::getAvailablePoints($userId);
        
        // Validation: insufficient points
        if ($pointsToRedeem > $availablePoints) {
            $result['error'] = "Insufficient points. Available: {$availablePoints}";
            return $result;
        }

        // Calculate discount
        $discountAmount = $pointsToRedeem * $pointsRatio;
        
        // Calculate max allowed discount based on percentage
        $maxAllowedDiscount = ($invoiceAmount * $maxPercentage) / 100;
        
        // Validation: discount exceeds max percentage
        if ($discountAmount > $maxAllowedDiscount) {
            $maxPoints = floor($maxAllowedDiscount / $pointsRatio);
            $result['error'] = "Maximum {$maxPercentage}% of invoice can be paid with points (max {$maxPoints} points)";
            return $result;
        }

        // Validation: discount cannot exceed invoice amount
        if ($discountAmount > $invoiceAmount) {
            $maxPoints = floor($invoiceAmount / $pointsRatio);
            $result['error'] = "Points value exceeds invoice amount. Maximum points: {$maxPoints}";
            return $result;
        }

        // Create redemption record
        RewardPoint::create([
            'user_id' => $userId,
            'invoice_id' => $invoiceId,
            'points' => -abs($pointsToRedeem),
            'transaction_type' => RewardPoint::TYPE_REDEEMED,
            'description' => "Redeemed for Invoice #{$invoiceId}",
            'expires_at' => null,
        ]);

        $result['points_redeemed'] = $pointsToRedeem;
        $result['discount_amount'] = $discountAmount;
        $result['final_amount'] = $invoiceAmount - $discountAmount;

        return $result;
    }
}
