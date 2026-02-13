function showLoader(text = 'Loading...', subtitle = 'Please wait...') {
    let loader = document.getElementById('uniqueGlobalLoader');

    if (!loader) {
        console.error('Loader element not found in DOM');
        return;
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

// Auto-hide on page load (DOMContentLoaded handled in layout)
window.addEventListener('load', function() {
    hideLoader();
});

// Safety timeout (15 seconds)
setTimeout(hideLoader, 15000);

// Prevent loader from staying if user navigates
window.addEventListener('beforeunload', function() {
    hideLoader();
});
