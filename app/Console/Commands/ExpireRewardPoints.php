<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireRewardPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rewards:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire reward points that have passed their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredPoints = \App\Models\RewardPoint::where('transaction_type', \App\Models\RewardPoint::TYPE_EARNED)
            ->where('expires_at', '<', now())
            ->whereNotNull('expires_at')
            ->get();

        $count = 0;
        foreach ($expiredPoints as $point) {
            // Create an expiration record
            \App\Models\RewardPoint::create([
                'user_id' => $point->user_id,
                'invoice_id' => $point->invoice_id,
                'points' => -abs($point->points), // Negative value to deduct
                'transaction_type' => \App\Models\RewardPoint::TYPE_EXPIRED,
                'description' => "Expired points from Invoice #{$point->invoice_id}",
                'expires_at' => null,
            ]);

            // Mark the original as expired by setting description
            $point->description = ($point->description ?? '') . ' [EXPIRED]';
            $point->save();
            
            $count++;
        }

        $this->info("Expired {$count} reward point(s).");
        return 0;
    }
}
