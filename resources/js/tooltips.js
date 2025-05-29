/**
 * Tooltips centralisés pour l'application
 * Ce fichier gère l'initialisation et la réinitialisation des tooltips dans toute l'application
 */

// Variable pour stocker les instances Tippy actives
let tippyInstances = [];

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
 * Détruit toutes les instances Tippy existantes
 */
function destroyAllTooltips() {
    // Détruire toutes les instances stockées
    tippyInstances.forEach(instance => {
        if (instance && typeof instance.destroy === 'function') {
            instance.destroy();
        }
    });
    
    // Vider le tableau des instances
    tippyInstances = [];
    
    // Détruire également toutes les instances Tippy attachées aux éléments existants
    if (window.tippy) {
        const elements = document.querySelectorAll('[data-tippy-content]');
        elements.forEach(element => {
            if (element._tippy) {
                element._tippy.destroy();
            }
        });
    }
}

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
        
        // Créer les tooltips avec notre configuration et stocker les instances
        const elements = document.querySelectorAll(selector);
        elements.forEach(element => {
            // Vérifier si l'élément a déjà un tooltip
            if (!element._tippy) {
                const instance = window.tippy(element, tooltipConfig);
                if (instance) {
                    tippyInstances.push(instance);
                }
            }
        });
    } else {
        console.error('Tippy.js n\'est pas disponible');
    }
}

/**
 * Initialise les tooltips pour toute l'application
 */
function initAllTooltips() {
    // D'abord détruire tous les tooltips existants
    destroyAllTooltips();
    
    // Puis initialiser tous les nouveaux tooltips
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
            setTimeout(() => initAllTooltips(), 150);
        });
    }
    
    // Écouteur pour l'événement personnalisé reload-tooltips
    document.addEventListener('reload-tooltips', () => {
        setTimeout(() => initAllTooltips(), 200);
    });
}

// Exporter les fonctions pour une utilisation externe
export { initTooltips, initAllTooltips, destroyAllTooltips, setupTooltipEventListeners };

// Auto-initialisation lors de l'importation
document.addEventListener('DOMContentLoaded', () => {
    setupTooltipEventListeners();
    initAllTooltips();
});