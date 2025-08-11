<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enrollment $enrollment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your payment has been accepted',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_accepted',
            with: [
                'enrollment' => $this->enrollment,
                'user' => $this->enrollment->user,
                'course' => $this->enrollment->course,
            ],
        );
    }
}


