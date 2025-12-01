<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExecuteSpaceManagementPolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'space:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the Space Management Policy defined in Settings to delete old order files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Space Management Policy execution...');

        $settings = Setting::first();

        if (!$settings) {
            $this->error('Settings not found.');
            return;
        }

        $duration = $settings->auto_delete_order_files_after;
        $delibleStatesJson = $settings->order_file_delible_states;

        if (empty($duration) || $duration === 'Forever') {
            $this->info('Space Management Policy is disabled or set to Forever.');
            return;
        }

        if (empty($delibleStatesJson)) {
            $this->info('No delible order states defined.');
            return;
        }

        $delibleStates = json_decode($delibleStatesJson, true);

        if (empty($delibleStates)) {
            $this->info('No delible order states defined.');
            return;
        }

        $cutoffDate = $this->getCutoffDate($duration);

        if (!$cutoffDate) {
            $this->error("Invalid duration: $duration");
            return;
        }

        $this->info("Cutoff date: " . $cutoffDate->toDateTimeString());
        $this->info("Delible states: " . implode(', ', $delibleStates));

        $mediaFiles = Media::where('usage', 'order')->orWhere('usage', 'Order')->get(); // Handle case sensitivity just in case
        $filesToDelete = [];

        $this->info("Found " . $mediaFiles->count() . " media files to check.");

        foreach ($mediaFiles as $media) {
            if (empty($media->data)) {
                continue;
            }

            $data = json_decode($media->data, true);

            if (!isset($data['orders']) || empty($data['orders'])) {
                continue;
            }

            // Check if media is linked to a product
            if (isset($data['products']) && !empty($data['products'])) {
                continue;
            }

            $orderIds = $data['orders'];
            $orders = Order::whereIn('id', $orderIds)->with('orderStatus')->get();

            if ($orders->isEmpty()) {
                // If linked orders don't exist anymore, maybe we should delete the file?
                // For safety, let's skip or maybe log it.
                // Policy says "files attached to orders". If orders are gone, it's an orphan.
                // Let's assume we only delete if we can verify the order status.
                continue;
            }

            $allOrdersEligible = true;

            foreach ($orders as $order) {
                $status = $order->orderStatus->name ?? null;
                
                // Check if status is in delible states
                if (!in_array($status, $delibleStates)) {
                    $allOrdersEligible = false;
                    break;
                }

                // Check if order is old enough
                // Using updated_at as the reference for when the order reached its current state
                if ($order->updated_at > $cutoffDate) {
                    $allOrdersEligible = false;
                    break;
                }
            }

            if ($allOrdersEligible) {
                $filesToDelete[] = $media->id;
            }
        }

        if (!empty($filesToDelete)) {
            $this->info("Deleting " . count($filesToDelete) . " files...");
            Media::deleteMedia($filesToDelete);
            $this->info("Deletion complete.");
        } else {
            $this->info("No files eligible for deletion.");
        }

        $this->info('Space Management Policy execution finished.');
    }

    private function getCutoffDate($duration)
    {
        $now = Carbon::now();

        return match ($duration) {
            'Two Weeks' => $now->subWeeks(2),
            'One Month' => $now->subMonth(),
            'Three Months' => $now->subMonths(3),
            'Six Months' => $now->subMonths(6),
            'One Year' => $now->subYear(),
            'Two Years' => $now->subYears(2),
            default => null,
        };
    }
}
