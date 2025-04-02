/**
 * Calculate and set layout height CSS variables
 * This sets --nav-height and --footer-height as CSS variables
 * that can be used throughout the application
 */
function updateLayoutHeights() {
    // Use requestAnimationFrame for better timing with the rendering cycle
    requestAnimationFrame(() => {
        const navbar = document.querySelector('nav') || { offsetHeight: 0 };
        const footer = document.querySelector('footer') || { offsetHeight: 0 };
        
        document.documentElement.style.setProperty('--nav-height', `${navbar.offsetHeight}px`);
        document.documentElement.style.setProperty('--footer-height', `${footer.offsetHeight}px`);
        
        // Add class to body indicating heights are calculated
        document.body.classList.add('layout-ready');
    });
}

// Calculate heights as early as possible in the page lifecycle
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateLayoutHeights);
} else {
    // DOM already ready, run immediately
    updateLayoutHeights();
}

// Update heights when window is resized
window.addEventListener('resize', updateLayoutHeights);

// Update heights when page content changes that might affect layout heights
document.addEventListener('livewire:load', updateLayoutHeights);
document.addEventListener('livewire:update', updateLayoutHeights);
