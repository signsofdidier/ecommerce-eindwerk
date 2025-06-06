<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\TenantService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // PDF IN MAIL
    public function build()
    {
        $company = TenantService::current(); // Haalt de actieve tenant op
        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $this->order,
            'company' => $company,
        ]);

        return $this->subject('Your order at ' . $company->name . ' is confirmed')
            ->view('emails.invoice-paid')
            ->with([
                'order' => $this->order,
                'company' => $company,
            ])
            ->attachData($pdf->output(), 'invoice-order-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}

