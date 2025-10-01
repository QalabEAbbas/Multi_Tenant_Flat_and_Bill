@component('mail::message')
# New Bill Created

A new bill has been created for flat **{{ $bill->flat->flat_number }}**.

- **Category:** {{ $bill->category->name }}
- **Month:** {{ $bill->month }}
- **Amount:** {{ $bill->amount }}
- **Due Amount:** {{ $bill->due_amount }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
