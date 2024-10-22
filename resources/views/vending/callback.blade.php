@extends('layouts.app')

@section('body')
<section class="d-flex flex-column align-items-center justify-content-center vh-100 vw-100">
    <div class="row">
        <div class="col-12">
            @if($purchase->status == \App\Models\WaterPurchase::STATUS_COMPLETED)
                <div class="alert text-center alert-success" role="alert">
                    Payment Completed
                </div>
            @elseif($purchase->status == \App\Models\WaterPurchase::STATUS_FAILED)
                <div class="alert text-center alert-danger" role="alert">
                    Payment Failed
                </div>
            @elseif($purchase->status == \App\Models\WaterPurchase::STATUS_PENDING)
                <div class="alert text-center alert-danger" role="alert">
                    Payment is pending, we will send an email once it's completed.
                </div>
            @endif
            <div class="card payment-card">
                <div class="card-body">
                    @if ($purchase->status == \App\Models\WaterPurchase::STATUS_COMPLETED)
                        <div class="responsive-table">
                            <table class="table-condensed w-100">
                                <tr>
                                    <td>Currency</td>
                                    <td class="text-end fw-bold">{{strtoupper($purchase->currency->code)}}</td>
                                </tr>
                                <tr>
                                    <td>Amount</td>
                                    <td class="text-end fw-bold">{{$purchase->currency->symbol}}{{money_currency($purchase->requested_amount)}}</td>
                                </tr>
                                @foreach ($purchase->formatted_tariffs as $item)
                                    @if($item['amount'] == 0) @continue @endif
                                    <tr>
                                        <td class="bg-danger-subtle text-danger">{{ucwords($item['name'])}}</td>
                                        <td class="text-end bg-danger-subtle text-danger fw-bold">-{{$purchase->currency->symbol}}{{money_currency($item['amount'])}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="border-bottom">Token Amount</td>
                                    <td class="text-end fw-bold border-bottom">{{$purchase->currency->symbol}}{{money_currency($purchase->token_amount)}}</td>
                                </tr>
                                <tr>
                                    <td>Volume</td>
                                    <td class="text-end fw-bold">{{$purchase->volume}}m<sup>3</sup></td>

                                </tr>
                                <tr>
                                    <td>Token</td>
                                    <td class="text-end fw-bold">{{$purchase->token}}</td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="text-center">please try again</div>
                    @endif
                </div>
            </div>
            <p class="text-center mt-5 fw-semibold text-muted">You can now go back to the app</p>
        </div>
    </div>
</section>
@endsection
