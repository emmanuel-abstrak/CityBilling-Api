@component('mail::message')
Good day,

Here is your receipt for <span style="color: #000000">{{ $purchase->property->meter }}</span>.

<div class="table">
    <table>
        <tr>
            <td>Currency</td>
            <td>{{strtoupper($purchase->currency->code)}}</td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>{{$purchase->currency->symbol}}{{money_currency($purchase->requested_amount)}}</td>
        </tr>
        @foreach ($purchase->formatted_tariffs as $item)
        <tr>
            <td>{{ucwords($item['name'])}}</td>
            <td style="color:red">-{{$purchase->currency->symbol}}{{money_currency($item['amount'])}}</td>
        </tr>
        @endforeach
        <tr>
            <td>Token Amount</td>
            <td>{{$purchase->currency->symbol}}{{money_currency($purchase->token_amount)}}</td>
        </tr>
        <tr>
            <td>Volume</td>
            <td>{{$purchase->volume}}</td>
        </tr>
        <tr>
            <td>Token</td>
            <td>{{$purchase->token}}</td>
        </tr>
    </table>
</div>

@endcomponent
