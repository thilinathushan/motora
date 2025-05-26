<div>
    @if($showModal)
        <div class="modal fade show" id="cryptoModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg p-3">
                    <form wire:submit="reAuth">
                        <div class="modal-header border-bottom-0">
                            <img src="{{ asset('metamask-icon.png') }}" alt="metamask-verification" class="me-2" style="width: 24px; height: 24px;">
                            <h5 class="modal-title fw-bold">MetaMask Verification Required</h5>
                            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Please confirm your identity to proceed with this sensitive blockchain action.</p>

                            @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="verification_code" class="form-label">Verification Code</label>
                                <input type="password" class="form-control" id="verification_code" wire:model="verification_code" required>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <div class="row w-100 m-0">
                                <button type="button" class="col me-1 btn btn-outline-secondary" wire:click="closeModal">Cancel</button>
                                <button type="submit" class="col ms-1 btn btn-primary">Sign & Proceed</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <script>
        // Generic script - no form-specific code
        document.addEventListener('livewire:init', () => {
            Livewire.on('crypto-verification-complete', () => {
                // Dispatch generic event that any form can listen to
                document.dispatchEvent(new CustomEvent('crypto-verified'));
            });

            Livewire.on('resubmit-pending-form', (event) => {
                // Generic form resubmission - works with any stored form
                if (window.pendingForm) {
                    window.pendingForm.submit();
                    window.pendingForm = null;
                }
            });
        });
    </script>
</div>
