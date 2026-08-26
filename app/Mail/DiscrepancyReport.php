<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Device;

class DiscrepancyReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $comments;
    public $device;
    public $category;

    public function __construct($user, $comments, $device, $category)
    {
        $this->user = $user;
        $this->comments = $comments;
        $this->device = $device;
        $this->category = $category;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Discrepancy Report from ' . $this->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.device.discrepancy-report',
            with: [
                'user' => $this->user,
                'comments' => $this->comments,
                'device' => $this->device,
                'category' => $this->category,
                'date' => now()->format('M j, Y \a\t g:i a')
            ]
        );
    }

    public function build()
    {
        return $this
            ->markdown('emails.discrepancy-report')
            ->with([
                'user' => $this->user,
                'comments' => $this->comments,
                'device' => $this->device,
                'category' => $this->category,
                'date' => now()->format('M j, Y \a\t g:i a')
            ]);
    }
}