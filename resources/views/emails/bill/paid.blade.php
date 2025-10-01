@component('mail::message')
# Bill Paid

The bill for flat **{{ $bill->flat->flat_number }}** has been paid.

- **Category:** {{ $bill->category->name }}
- **Month:** {{ $bill->month }}
- **Amount Paid:** {{ $bill->amount }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
