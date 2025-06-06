<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\TenantService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function build()
    {
        $company = TenantService::current();

        $pdf = Pdf::loadView('pdf.order-placed', [
            'order' => $this->order,
            'company' => $company,
        ]);

        return $this->subject('Your order at ' . $company->name . ' was placed successfully')
            ->view('emails.order-placed')
            ->with([
                'order' => $this->order,
                'company' => $company,
            ])
            ->attachData($pdf->output(), 'order-confirmation-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

}
