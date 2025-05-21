<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($vehicle, $user, $organization, $action_url)
    {
        $this->vehicle = $vehicle;
        $this->user = $user;
        $this->organization = $organization;
        $this->action_url = $action_url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'vehicle_id' => $this->vehicle->id,
            'vehicle_registration_number' => $this->vehicle->registration_number,
            'requested_by' => $this->user->name,
            'requested_by_id' => $this->user->id,
            'organization_id' => $this->organization['id'],
            'location_id' => $this->organization['location_id'],
            'message' => 'Vehicle verification request has been sent by ' . $this->user->name . ' for ' . $this->vehicle->registration_number . ' vehicle.' . 'Please verify the vehicle.',
            'action_url' => $this->action_url,
        ];
    }
}
