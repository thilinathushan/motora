<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationPanel extends Component
{
    public $notifications = [];

    public function loadNotifications()
    {
        if (Auth::guard('organization_user')->check() || Auth::guard('web')->check()) {
            if(Auth::guard('organization_user')->user()){
                $user = Auth::guard('organization_user')->user();
            }
            if(Auth::guard('web')->user()){
                $user = Auth::guard('web')->user();
            }   
            $this->notifications = $user->unreadNotifications;
        }
    }

    public function render()
    {
        $this->loadNotifications();
        return view('livewire.notification-panel');
    }
}
