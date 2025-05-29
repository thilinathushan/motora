class MetaMaskHandler {
    constructor() {
        this.isConnected = false;
        this.currentAccount = null;
        this.ready = this.init();
    }

    async init() {
        // Check if MetaMask is installed
        if (typeof window.ethereum !== "undefined") {
            // console.log("MetaMask is installed!");

            // Check if already connected
            const accounts = await window.ethereum.request({
                method: "eth_accounts",
            });
            // console.log('accounts', accounts);
            // console.log('accounts length', accounts.length);
            if (accounts.length > 0) {
                this.isConnected = true;
                this.currentAccount = accounts[0];
                this.updateUI();
                // console.log(
                //     "Already connected to MetaMask with account:",
                //     this.currentAccount
                // );
            }

            // Listen for account changes
            window.ethereum.on("accountsChanged", (accounts) => {
                if (accounts.length > 0) {
                    this.isConnected = true;
                    this.currentAccount = accounts[0];
                } else {
                    this.isConnected = false;
                    this.currentAccount = null;
                }
                this.updateUI();
            });

            // Listen for chain changes
            window.ethereum.on("chainChanged", () => {
                window.location.reload();
            });
        } else {
            // console.log("MetaMask is not installed.");
            this.showInstallPrompt();
        }
    }

    async connectWallet() {
        if (typeof window.ethereum === "undefined") {
            // console.error("MetaMask not installed");
            this.showInstallPrompt();
            return false;
        }

        try {
            // Request account access
            const accounts = await window.ethereum.request({
                method: "eth_requestAccounts",
            });

            if (accounts.length > 0) {
                this.isConnected = true;
                this.currentAccount = accounts[0];
                this.updateUI();

                // console.log(
                //     "Wallet connected successfully:",
                //     this.currentAccount
                // );
                // save wallet address to backend
                const saved = await this.saveWalletAddress(this.currentAccount);
                return saved ? this.currentAccount : false;
            } else {
                // console.error("No accounts returned from MetaMask");
                return false;
            }
        } catch (error) {
            // console.error("Error connecting to MetaMask:", error);
            this.showError("Failed to connect to MetaMask. Please try again.");
            return false;
        }
    }

    async signMessage(message, addressToSignWith) {
        // 1. Defensive check: Ensure an address is provided
        if (!addressToSignWith) {
            // console.error(
            //     "Signature failed: No address was provided to sign with."
            // );
            throw new Error("Signature failed: No address was provided.");
        }

        // 2. Defensive check: Ensure the wallet is still connected to that address
        if (
            !this.isConnected ||
            this.currentAccount?.toLowerCase() !==
                addressToSignWith.toLowerCase()
        ) {
            // console.error(
            //     "Signature failed: MetaMask is not connected with the expected account.",
            //     {
            //         expected: addressToSignWith,
            //         actual: this.currentAccount,
            //     }
            // );
            throw new Error(
                "The connected wallet does not match the required address."
            );
        }

        try {
            // 3. Use the EXPLICITLY passed address for the signature
            const signature = await window.ethereum.request({
                method: "personal_sign",
                params: [message, addressToSignWith], // Use the passed-in address
            });

            return signature;
        } catch (error) {
            // console.error("Error during personal_sign:", error);
            throw error; // Re-throw the original error to be caught by the caller
        }
    }

    async saveWalletAddress(address) {
        try {
            // console.log("Saving wallet address to backend...");
            const response = await fetch("/wallet/connect", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ wallet_address: address }),
            });

            const data = await response.json();
            if (data.success) {
                // console.log("Wallet address saved successfully.");
                return true;
            } else {
                this.showError(
                    data.message || "Failed to save wallet address."
                );
                return false;
            }
        } catch (error) {
            // console.error("Error saving wallet address:", error);
            this.showError("Network error while saving wallet address.");
            return false;
        }
    }

    updateUI() {
        // Update connect button
        const connectBtn = document.getElementById("connectWalletBtn");
        const walletInfo = document.getElementById("walletInfo");
        const walletAddress = document.getElementById("walletAddress");

        if (connectBtn) {
            if (this.isConnected) {
                connectBtn.textContent = "Connected";
                connectBtn.classList.remove("btn-primary");
                connectBtn.classList.add("btn-success");
                connectBtn.disabled = true;
            } else {
                connectBtn.textContent = "Connect MetaMask";
                connectBtn.classList.remove("btn-success");
                connectBtn.classList.add("btn-primary");
                connectBtn.disabled = false;
            }
        }

        if (walletInfo && this.isConnected) {
            walletInfo.style.display = "block";
            if (walletAddress) {
                walletAddress.textContent = this.formatAddress(
                    this.currentAccount
                );
            }
        } else if (walletInfo) {
            walletInfo.style.display = "none";
        }
    }

    formatAddress(address) {
        if (!address) return "";
        return `${address.substring(0, 6)}...${address.substring(
            address.length - 4
        )}`;
    }

    showInstallPrompt() {
        const installPrompt = document.getElementById("metamaskInstallPrompt");
        if (installPrompt) {
            installPrompt.style.display = "block";
        }
    }

    showError(message) {
        // Create a toast notification or use your existing notification system
        if (typeof window.showNotification === "function") {
            window.showNotification(message, "error");
        } else {
            alert(message);
        }
    }

    // Method to get current account (useful for other parts of the app)
    getCurrentAccount() {
        return this.currentAccount;
    }

    // Method to check if connected (useful for other parts of the app)
    isWalletConnected() {
        return this.isConnected;
    }
}

// Make it globally available
window.MetaMaskHandler = MetaMaskHandler;

// Auto-initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.MetaMaskHandler === "undefined") {
        window.MetaMaskHandler = new MetaMaskHandler();
    }
});

// Export for ES6 modules (if needed elsewhere)
export default MetaMaskHandler;
