<?php

namespace App\Mail;

use App\Models\OrganizationUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganizationUserSetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $link;
    public $currentUser;

    /**
     * Create a new message instance.
     */
    public function __construct(OrganizationUser $currentUser, OrganizationUser $user, string $link)
    {
        $this->currentUser = $currentUser;
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->currentUser->email),
            subject: 'Welcome to Motora – Set Your Password to Get Started for '.$this->currentUser->organization->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.organization_user.set_password',
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