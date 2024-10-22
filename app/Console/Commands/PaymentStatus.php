<?php

namespace App\Console\Commands;

use App\Helpers\PaynowHelper;
use App\Models\WaterPurchase;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PaymentStatus extends Command
{
    protected $signature = 'app:payment-status';
    protected $description = 'Command description';

    public function handle()
    {
        $this->warn("Payment status cron starting.....");
        WaterPurchase::query()
            ->where('status', WaterPurchase::STATUS_PROCESSING)
            ->orWhere('status', WaterPurchase::STATUS_PENDING)
            ->orderBy('id', 'ASC')
            ->where('created_at', '<', Carbon::now()->subMinute())
            ->each(function ($purchase) {
                $this->info("$purchase->id .......... {$purchase->status}");

                $paynowHelper = new PaynowHelper($purchase);
                $paynowHelper->settlePayment();
            });

        return 0;
    }
}
