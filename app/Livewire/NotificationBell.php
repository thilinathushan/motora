<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{

    public $notificationsCount = 0;

    public function loadNotificationsCount()
    {
        if (Auth::guard('organization_user')->check() || Auth::guard('web')->check()) {
            if(Auth::guard('organization_user')->user()){
                $user = Auth::guard('organization_user')->user();
            }
            if(Auth::guard('web')->user()){
                $user = Auth::guard('web')->user();
            }    
            $this->notificationsCount = $user->unreadNotifications->count();
        }
    }

    public function render()
    {
        $this->loadNotificationsCount();
        return view('livewire.notification-bell');
    }
}
