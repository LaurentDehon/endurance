/**
 * Tooltips centralisés pour l'application
 * Ce fichier gère l'initialisation et la réinitialisation des tooltips dans toute l'application
 */

// Configuration unique pour tous les tooltips
const tooltipConfig = {
    allowHTML: true,
    arrow: true,
    placement: 'top',
    duration: [200, 100],
    delay: [100, 0],
    // Styles appliqués directement via JavaScript
    theme: 'custom',
    // Ces options permettent d'appliquer des styles spécifiques
    popperOptions: {
        modifiers: [{
            name: 'applyStyles',
            options: {
                // S'assurer que les styles sont toujours appliqués
                gpuAcceleration: false
            }
        }]
    }
};

/**
 * Initialise les tooltips pour un sélecteur donné
 * @param {string} selector - Le sélecteur CSS pour cibler les éléments
 */
function initTooltips(selector = '[data-tippy-content]') {
    if (window.tippy) {
        // Ajouter un thème personnalisé à Tippy
        if (window.tippy.setDefaultProps) {
            window.tippy.setDefaultProps({
                theme: 'custom'
            });
        }
        
        // Créer les tooltips avec notre configuration
        window.tippy(selector, tooltipConfig);
    } else {
        console.error('Tippy.js n\'est pas disponible');
    }
}

/**
 * Initialise les tooltips pour toute l'application
 */
function initAllTooltips() {
    // Initialiser tous les tooltips avec une seule configuration
    initTooltips('[data-tippy-content]');
}

/**
 * Configure les écouteurs d'événements pour réinitialiser les tooltips
 */
function setupTooltipEventListeners() {
    // Événements Livewire standard
    document.addEventListener('livewire:navigated', () => initAllTooltips());
    document.addEventListener('livewire:init', () => initAllTooltips());
    document.addEventListener('DOMContentLoaded', () => initAllTooltips());
    
    // Réinitialiser après chaque mise à jour Livewire
    if (window.Livewire) {
        window.Livewire.hook('message.processed', () => {
            setTimeout(() => initAllTooltips(), 100);
        });
    }
    
    // Écouteur pour l'événement personnalisé reload-tooltips
    document.addEventListener('reload-tooltips', () => {
        setTimeout(() => initAllTooltips(), 100);
    });
}

// Exporter les fonctions pour une utilisation externe
export { initTooltips, initAllTooltips, setupTooltipEventListeners };

// Auto-initialisation lors de l'importation
document.addEventListener('DOMContentLoaded', () => {
    setupTooltipEventListeners();
    initAllTooltips();
});