<?php

namespace App\Livewire;

use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class ReauthenticationModal extends Component
{
    public $showModal = false;
    public $walletConnected = false;
    public $connectedWalletAddress = null;
    public $isProcessing = false;
    public $processingMessage = '';
    public $errorMessage = '';
    public $currentStep = 1;
    public $verificationSuccess = false;
    public $successMessage = '';
    public string $connectButtonState = 'default';

    public function mount()
    {
        $this->resetModalState();
    }

    public function connect()
    {
        $this->connectButtonState = 'connecting';
        $this->errorMessage = '';

        // Dispatch a browser event to tell our JavaScript to open MetaMask.
        $this->dispatch('start-metamask-connection');
    }

    #[On('show-crypto-modal')]
    public function showModal()
    {
        $this->showModal = true;
        $this->resetModalState();
        $this->checkExistingWalletConnection();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetModalState();
    }

    public function checkExistingWalletConnection()
    {
        $user = Auth::guard('organization_user')->user();
        if ($user && $user->hasWalletConnected()) {
            $this->walletConnected = true;
            $this->connectedWalletAddress = $user->wallet_address;
            $this->currentStep = 2;
        } else {
            $this->currentStep = 1;
        }
    }

    #[On('wallet-connected')]
    public function handleWalletConnected($walletAddress, WalletController $walletController)
    {
        $this->walletConnected = true;
        $this->connectedWalletAddress = $walletAddress;
        $this->errorMessage = '';
        $this->currentStep = 2;
        $this->connectButtonState = 'default';

        // Save to session temporarily
        session(['temp_wallet_address' => $walletAddress]);

        $this->syncWalletStatus($walletAddress, $walletController);

        // Emit success event for frontend
        $this->dispatch('wallet-connection-success', [
            'address' => $walletAddress,
            'step' => 2
        ]);
    }

    public function handleWalletError($errorMessage)
    {
        $this->errorMessage = 'Wallet connection failed: ' . $errorMessage;
        $this->walletConnected = false;
        $this->currentStep = 1;
        $this->connectButtonState = 'default';

        // Emit error event for frontend
        $this->dispatch('wallet-connection-error', [
            'message' => $errorMessage
        ]);
    }

    public function authenticate(WalletController $walletController)
    {
        if (!$this->walletConnected || !$this->connectedWalletAddress) {
            $this->errorMessage = 'Please connect your wallet first.';
            return;
        }

        $this->isProcessing = true;
        $this->processingMessage = 'Preparing verification message...';
        $this->errorMessage = '';

        // 1. Call your new controller endpoint to get the message
        $response = $walletController->generateMessage();
        $data = json_decode($response->getContent(), true);

        if ($data['success']) {
            // 2. Dispatch the message from the server to the client for signing
            $this->dispatch('wallet-signature-required', [
                'message' => $data['message'],
                'address' => $this->connectedWalletAddress
            ]);
        } else {
            $this->isProcessing = false;
            $this->errorMessage = $data['message'] ?? 'Failed to prepare verification.';
        }
    }

    public function handleSignatureComplete($signature, WalletController $walletController)
    {
        $this->processingMessage = 'Verifying signature on the server...';

        // Prepare a new Request object with the data for the controller method
        $request = new Request([
            'signature' => $signature,
            'wallet_address' => $this->connectedWalletAddress,
        ]);

        // Call the controller method directly
        $response = $walletController->verify($request);
        $data = json_decode($response->getContent(), true);

        $this->isProcessing = false;

        if ($data['success']) {
            // 2. Verification was a success! Close the modal and notify the parent component.
            $this->verificationSuccess = true;
            $this->successMessage = "Verification successful! Submitting form...";

            // Schedule delayed actions
            $this->scheduleDelayedActions();
        } else {
            // 3. Verification failed, show the error from the server.
            $this->errorMessage = $data['message'] ?? 'Signature verification failed.';
        }
    }

    private function scheduleDelayedActions()
    {
        // Don't close modal immediately - let JavaScript handle the timing
        $this->dispatch('schedule-delayed-resubmit');
    }

    public function handleSignatureError($errorMessage)
    {
        $this->isProcessing = false;
        $this->errorMessage = 'Signature failed: ' . $errorMessage;
    }

    public function getConnectedWalletAddress()
    {
        if (!$this->connectedWalletAddress) {
            return '';
        }

        return substr($this->connectedWalletAddress, 0, 6) . '...' . substr($this->connectedWalletAddress, -4);
    }

    private function resetModalState()
    {
        $this->walletConnected = false;
        $this->connectedWalletAddress = null;
        $this->isProcessing = false;
        $this->processingMessage = '';
        $this->errorMessage = '';
        $this->currentStep = 1;
    }

    public function syncWalletStatus(string $walletAddress, WalletController $walletController)
    {
        // 1. Prepare a request to save the address to the database
        $connectRequest = new Request(['wallet_address' => $walletAddress]);
        $connectResponse = $walletController->connect($connectRequest);
        $data = json_decode($connectResponse->getContent(), true);

        // 2. Check if the connection was saved successfully
        if ($data['success']) {
            // 3. If yes, update the component state and move to Step 2
            $this->walletConnected = true;
            $this->connectedWalletAddress = $walletAddress;
            $this->currentStep = 2;
        } else {
            // If saving fails (e.g., wallet taken by another user), show an error
            $this->errorMessage = $data['message'] ?? 'Failed to associate wallet with your account.';
        }
    }

    public function render()
    {
        return view('livewire.reauthentication-modal');
    }
}
