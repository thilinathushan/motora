<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ReauthenticationModal extends Component
{
    public $showModal = false;
    public $verification_code = '';

    #[On('show-crypto-modal')]
    public function showModal()
    {
        $this->showModal = true;
    }

    public function reAuth()
    {
        if (!empty($this->verification_code)) {
            // Set crypto verification flags
            session([
                'crypto_verified' => true,
                'resubmitting_form' => true,
            ]);

            $this->showModal = false;
            $this->verification_code = '';

            // Clear modal trigger
            session()->forget('show_crypto_modal');

            // Dispatch event with form data
            $this->dispatch('resubmit-form', [
                'action' => session('pending_form_action'),
                'data' => session('pending_form_data'),
                'method' => session('pending_form_method', 'POST'),
            ]);
        } else {
            session()->flash('error', 'Verification code is required');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        session()->forget([
            'show_crypto_modal',
            'pending_form_action',
            'pending_form_data',
            'pending_form_method'
        ]);
    }

    public function mount()
    {
        // Show modal if triggered by session
        $this->showModal = session('show_crypto_modal', false);
    }

    public function render()
    {
        return view('livewire.reauthentication-modal');
    }
}
