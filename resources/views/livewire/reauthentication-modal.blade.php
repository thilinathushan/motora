<div>
    @if($showModal)
        <div class="modal fade show" id="cryptoModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg p-3">
                    <form wire:submit.prevent="authenticate">
                        <div class="modal-header border-bottom-0">
                            <img src="{{ asset('metamask-icon.png') }}" alt="metamask-verification" class="me-2" style="width: 24px; height: 24px;">
                            <h5 class="modal-title fw-bold">MetaMask Verification Required</h5>
                            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if(!$verificationSuccess)
                                <p>Please confirm your identity to proceed with this sensitive blockchain action.</p>

                                {{-- Step Progess Indicator --}}
                                <div class="step-progress mb-4" wire:key="progress-{{ $currentStep }}">
                                    <div class="d-flex justify-content-between">
                                        <div class="step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
                                            <div class="step-circle">
                                                @if ($currentStep > 1)
                                                    <i class="fi fi-rr-check-circle"></i>
                                                @else
                                                    1
                                                @endif
                                            </div>
                                            <small>Connect Wallet</small>
                                        </div>
                                        <div class="step-line {{ $currentStep > 1 ? 'completed' : '' }}"></div>
                                        <div class="step {{ $currentStep >= 2 ? 'active' : '' }}">
                                            <div class="step-circle">2</div>
                                            <small>Sign Message</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Step 1: Connect Wallet --}}
                                @if ($currentStep == 1)
                                    <div class="wallet-connection-section" wire:key="step-1-{{ now()->timestamp }}">
                                        <div class="alert alert-info">
                                            <h6><i class="fi fi-rr-wallet me-2"></i>Step 1: Connect Wallet</h6>
                                            <p class="mb-3">Connect your MetaMask wallet to proceed with verification.</p>

                                            <!-- MetaMask Install Prompt -->
                                            <div id="metamaskInstallPrompt" class="alert alert-warning" style="display: none;">
                                                <small>
                                                    <strong>MetaMask Required:</strong>
                                                    <a href="https://metamask.io/download/" target="_blank">Install MetaMask</a>
                                                </small>
                                            </div>

                                            @if ($connectButtonState === 'default')
                                                <button type="button" wire:click="connect" wire:loading.attr="disabled" class="btn btn-primary d-inline-flex align-items-center">
                                                    <i class="fi fi-rr-wallet me-1"></i>
                                                    <span>Connect MetaMask</span>
                                                </button>
                                            @elseif ($connectButtonState === 'connecting')
                                                <button type="button" class="btn btn-primary d-inline-flex align-items-center" disabled>
                                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                    <span>Connecting...</span>
                                                </button>
                                            @endif

                                            <!-- Connection Status -->
                                            <div id="walletConnectionStatus" style="display: none;" class="mt-2">
                                                <small class="text-success">
                                                    <i class="fi fi-rr-check-circle me-1"></i>
                                                    Wallet connected: <span id="connectedWalletAddress"></span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Step 2: Verification --}}
                                @if($currentStep == 2)
                                    <div class="verification-section" wire:key="step-2-{{ now()->timestamp }}">
                                        <div class="alert alert-success">
                                            <h6><i class="fi fi-rr-check-circle me-2"></i>Wallet Connected</h6>
                                            <small>Address: {{ $this->getConnectedWalletAddress() }}</small>
                                        </div>

                                        <div class="alert alert-warning">
                                            <h6><i class="fi fi-rr-document-signed me-2"></i>Step 2: Sign Message</h6>
                                            <p class="mb-0">Click "Sign & Proceed" to sign the verification message with your wallet.</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Loading State --}}
                                @if($isProcessing)
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Processing...</span>
                                        </div>
                                        <p class="mt-2 mb-0">{{ $processingMessage ?? 'Processing verification...' }}</p>
                                    </div>
                                @endif

                                {{-- Error Message --}}
                                @if ($errorMessage)
                                    <div class="alert alert-danger">
                                        <i class="fi fi-rr-exclamation me-2"></i>
                                        {{ $errorMessage }}
                                    </div>
                                @endif
                            @endif

                            {{-- Step 3: Success State --}}
                            @if($verificationSuccess)
                                <div class="verification-success" wire:key="step-success-{{ now()->timestamp }}">
                                    <div class="alert alert-success text-center">
                                        <div class="mb-3">
                                            <i class="fi fi-rr-check-circle text-success" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5 class="text-success mb-2">Verification Successful!</h5>
                                        <p class="mb-2">{{ $successMessage }}</p>
                                        <small class="text-muted">This window will close automatically...</small>
                                    </div>

                                    <div class="text-center py-2">
                                        <div class="spinner-border spinner-border-sm text-success" role="status">
                                            <span class="visually-hidden">Processing...</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-top-0" wire:key="footer-{{ $currentStep }}">
                            <div class="row w-100 m-0">
                                <button type="button" class="col me-1 btn btn-outline-secondary" wire:click="closeModal">Cancel</button>
                                @if ($currentStep == 1)
                                    <button type="button" class="col ms-1 btn btn-secondary" disabled>
                                        Connect Wallet First
                                    </button>
                                @elseif($currentStep == 2)
                                    <button type="submit" class="col ms-1 btn btn-primary"
                                        @if($isProcessing) disabled @endif>
                                        @if($isProcessing)
                                            <span
                                                class="spinner-border spinner-border-sm align-middle me-2"
                                                style="width: 1rem; height: 1rem;"
                                                role="status"
                                                aria-hidden="true">
                                            </span>
                                        @endif
                                        Sign & Proceed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-crypto-modal', (event) => {
            // a small timeout to ensure the modal DOM exists
            setTimeout(checkInitialWalletStatus, 50);
        });

        Livewire.on('wallet-signature-required', (event) => {
            // The event data from Livewire is an array, so event[0] is correct
            if (event && event[0]) {
                signMessageForVerification(event[0].message, event[0].address);
            }
        });

        @this.on('start-metamask-connection', async () => {

            if (!window.metaMaskHandler) {
                // If MetaMask isn't available, report the error back to Livewire
                @this.call('handleWalletError', 'MetaMask handler is not available.');
                return;
            }

            try {
                // Try to connect the wallet
                const address = await window.metaMaskHandler.connectWallet();
                if (address) {
                    // Success! Report the address back to Livewire.
                    @this.call('handleWalletConnected', address);
                } else {
                    // Handle the case where the user closes the pop-up without choosing an account
                    @this.call('handleWalletError', 'Connection returned no address.');
                }
            } catch (error) {
                // Failure! Report the error message back to Livewire.
                @this.call('handleWalletError', error.message || 'User rejected the request.');
            }
        });
    });

    async function checkInitialWalletStatus() {
        // console.log('Checking initial wallet status...');

        if (!window.metaMaskHandler) {
            // console.error('Handler not initialized.');
            return;
        }

        try {
            // 1. Wait for the init() promise to resolve. This solves the race condition.
            await window.metaMaskHandler.ready;
            // console.log('MetaMask handler is now ready.');

            // 2. Now that we've waited, this check is 100% reliable.
            if (window.metaMaskHandler.isWalletConnected()) {
                const address = window.metaMaskHandler.getCurrentAccount();
                // console.log('Wallet IS already connected. Syncing with Livewire. Address:', address);

                // 3. Tell Livewire to move to Step 2.
                @this.call('syncWalletStatus', address);

            } else {
                // console.log('Wallet is NOT connected. Modal will remain on Step 1.');
            }
        } catch (error) {
            // console.error('Error during initial wallet status check:', error);
        }
    }

    // async function connectWalletForVerification() {
    //     const connectBtn = document.getElementById('connectWalletBtn');
    //     const spinnerSpan = document.getElementById('btn-spinner');
    //     const textSpan = document.getElementById('btn-text');

    //     if (!window.metaMaskHandler) {
    //         // console.error('MetaMask handler instance is not available.');
    //         return;
    //     }

    //     connectBtn.disabled = true;
    //     textSpan.style.display = 'none';
    //     spinnerSpan.style.display = 'inline-flex';

    //     try {
    //         const address = await window.metaMaskHandler.connectWallet();

    //         if (address) {
    //             // console.log('Wallet connected successfully:', address);

    //             // Call the existing Livewire method.
    //             @this.call('handleWalletConnected', address);
    //             document.querySelector('#btn-text span').textContent = 'Connected!';
    //             document.querySelector('#btn-text i').className = 'fi fi-rs-check-circle me-1 text-success';
    //         } else {
    //             if(connectBtn) {
    //                 connectBtn.disabled = false;
    //                 connectBtn.innerHTML = 'Connect MetaMask';
    //             }
    //         }
    //     } catch (error) {
    //         // console.error('Wallet connection failed:', error);
    //         @this.call('handleWalletError', error.message || 'Connection failed.');
    //     } finally {
    //         connectBtn.disabled = false;
    //         spinnerSpan.style.display = 'none';
    //         textSpan.style.display = 'inline-flex';
    //     }
    // }

    async function signMessageForVerification(message, address) {
        if (typeof window.metaMaskHandler?.signMessage !== 'function') {
            // console.error('signMessage function not available on metaMaskHandler');
            @this.call('handleSignatureError', 'Client-side script error.');
            return;
        }

        try {
            // Pass both parameters to the handler
            // console.log('message', message);
            // console.log('address', address);
            const signature = await window.metaMaskHandler.signMessage(message, address);

            // On success, call back to Livewire
            @this.call('handleSignatureComplete', signature);

        } catch (error) {
            // console.error('Error signing message:', error);
            @this.call('handleSignatureError', error.message || 'The signature request was rejected.');
        }
    }
</script>