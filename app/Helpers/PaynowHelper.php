<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Mail\PurchaseReceiptMail;
use App\Models\PropertyStatementItem;
use App\Models\WaterPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Paynow\Payments\Paynow;

class PaynowHelper
{
    private string $api_id;
    private string $api_key;

    public function __construct(private readonly WaterPurchase $waterPurchase)
    {
        $currency = strtolower($waterPurchase->currency->code);
        $this->api_id = config("paynow.$currency.id");
        $this->api_key = config("paynow.$currency.key");
    }
    public function getInstance(): Paynow
    {
        return new Paynow(
            $this->api_id,
            $this->api_key,
            returnUrl: route('vending.callback', $this->waterPurchase->id, true),
            resultUrl: route('vending.callback', $this->waterPurchase->id, true),
        );
    }

    public function settlePayment(): void
    {
        if ($this->waterPurchase->poll_url) {
            $status = $this->getInstance()->pollTransaction($this->waterPurchase->poll_url);
            if ($status->paid() || strtolower($status->status()) == 'awaiting delivery' || strtolower($status->status()) == 'settled') {
                $this->waterPurchase->status = WaterPurchase::STATUS_COMPLETED;
                if ($this->waterPurchase->token_amount > 0) {
                    $tokenDetail = VendingHelper::buyToken($this->waterPurchase);
                    if ($tokenDetail) {
                        $this->waterPurchase->token = $tokenDetail->getToken();
                    }
                }
                $this->payTariffs();
            } elseif (in_array(strtolower($status->status()), ['cancelled', 'frozen', 'failed'])) {
                $this->waterPurchase->status = WaterPurchase::STATUS_FAILED;
            } elseif (Carbon::now()->diffInHours($this->waterPurchase->created_at) > 1) {
                $this->waterPurchase->status = WaterPurchase::STATUS_ABANDONED;
            }
        } else {
            $this->waterPurchase->status = WaterPurchase::STATUS_FAILED;
        }

        $this->waterPurchase->save();
    }

    private function payTariffs(): void
    {
        if ($tariffs = $this->waterPurchase->getAttribute('formatted_tariffs')) {
            foreach ($tariffs as $tariff) {
                $statementItem = PropertyStatementItem::query()->where('id', $tariff['statement_item_id'])->first();
                if ($statementItem) {
                    if($statementItem->paid < $statementItem->total) {
                        $statementItem->paid += $tariff['amount'];
                    } else {
                        $statementItem->paid = $statementItem->total;
                    }
                    $statementItem->save();

                    if($statementItem->statement->paid < $statementItem->statement->total) {
                        $statementItem->statement->paid += $tariff['amount'];
                    } else {
                        $statementItem->statement->paid = $statementItem->statement->total;
                    }
                    $statementItem->statement->save();
                }
            }
        }
    }
}
