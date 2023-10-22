<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactRestaurant extends Mailable
{
    use Queueable, SerializesModels;

    public $dataMessage;
    public $restaurantData;

    /**
     * Create a new message instance.
     */
    public function __construct($dataMessage, $restaurantData)
    {
        $this->dataMessage = $dataMessage;
        $this->restaurantData = $restaurantData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->dataMessage['email'], $this->restaurantData['name'].' - '.$this->dataMessage['name']),
            subject: $this->dataMessage['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contactrestaurant',
            with: [
                'messages' => $this->dataMessage['message'],
                'logoRestaurant' => $this->restaurantData['logo'],
                'nameRestaurant' => $this->restaurantData['name']
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
