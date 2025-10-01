<?php
namespace App\Mail;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BillPaidNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $bill;
    public $paidBy;

    public function __construct(Bill $bill, $paidBy)
    {
        $this->bill = $bill;
        $this->paidBy = $paidBy;
    }

    public function build()
    {
        return $this->subject("Bill Paid - Flat {$this->bill->flat->flat_number}")
                    ->markdown('emails.bill.paid');
    }
}
