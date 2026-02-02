function showLoader(text = 'Loading...', subtitle = 'Please wait...') {
    let loader = document.getElementById('uniqueGlobalLoader');

    if (!loader) {
        // Create loader element
        loader = document.createElement('div');
        loader.id = 'uniqueGlobalLoader';
        loader.className = 'unique-global-loader';
        loader.innerHTML = `
            <div class="unique-loader-background"></div>
            <div class="unique-loader-container">
                <div class="unique-loader-brand">
                    <i class="fa-solid fa-leaf"></i>
                </div>

                <div class="unique-loader-spinner-wrapper">
                    <div class="unique-spinner-outer"></div>
                    <div class="unique-spinner-middle"></div>
                    <div class="unique-spinner-inner"></div>
                </div>

                <div class="unique-loader-dots">
                    <div class="unique-dot"></div>
                    <div class="unique-dot"></div>
                    <div class="unique-dot"></div>
                </div>

                <div class="unique-loader-text">Loading...</div>
                <div class="unique-loader-subtitle">Please wait...</div>

                <div class="unique-loader-progress">
                    <div class="unique-progress-bar"></div>
                </div>
            </div>
        `;
        document.body.appendChild(loader);
    }

    // Update text
    const textElement = loader.querySelector('.unique-loader-text');
    const subtitleElement = loader.querySelector('.unique-loader-subtitle');

    if (textElement) {
        textElement.textContent = text;
    }

    if (subtitleElement) {
        subtitleElement.textContent = subtitle;
    }

    // Show loader with animation
    requestAnimationFrame(() => {
        loader.classList.add('unique-active');
    });
}

function hideLoader() {
    const loader = document.getElementById('uniqueGlobalLoader');
    if (loader) {
        loader.classList.remove('unique-active');
    }
}

// Quick variants for common messages
function showLoadingCart() {
    showLoader('Loading Cart...', 'Fetching your items');
}

function showUpdatingCart() {
    showLoader('Updating Cart...', 'Processing your request');
}

function showRemovingItem() {
    showLoader('Removing Item...', 'Please wait a moment');
}

function showClearingCart() {
    showLoader('Clearing Cart...', 'Removing all items');
}

function showProcessingOrder() {
    showLoader('Processing Order...', 'Almost done!');
}

// Auto-hide on page load
window.addEventListener('load', function() {
    hideLoader();
});

// Safety timeout (15 seconds)
setTimeout(hideLoader, 15000);

// Prevent loader from staying if user navigates
window.addEventListener('beforeunload', function() {
    hideLoader();
});
