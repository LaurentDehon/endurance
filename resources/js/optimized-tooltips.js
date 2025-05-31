/**
 * Système optimisé de gestion des tooltips
 * Évite la destruction/recréation complète et cible seulement les éléments modifiés
 */

// Cache des instances Tippy existantes pour éviter les recréations inutiles
let tippyInstancesCache = new Map();
let lastTooltipUpdate = 0;

/**
 * Initialise les tooltips de manière optimisée
 * Réutilise les instances existantes quand possible
 */
function initOptimizedTooltips() {
    // Débounce pour éviter les appels multiples rapprochés
    const now = Date.now();
    if (now - lastTooltipUpdate < 100) {
        return;
    }
    lastTooltipUpdate = now;

    // Sélectionner seulement les éléments sans tooltip existant
    const elementsWithTooltips = document.querySelectorAll('[data-tippy-content]:not([data-tippy-initialized])');
    
    if (elementsWithTooltips.length === 0) {
        return;
    }

    // Traitement par batch pour améliorer les performances
    const batchSize = 10;
    let currentBatch = 0;
    
    function processBatch() {
        const start = currentBatch * batchSize;
        const end = Math.min(start + batchSize, elementsWithTooltips.length);
        
        for (let i = start; i < end; i++) {
            const element = elementsWithTooltips[i];
            const elementId = element.dataset.elementId || `tooltip-${i}`;
            
            // Vérifier si une instance existe déjà
            if (!tippyInstancesCache.has(elementId)) {
                try {
                    const instance = tippy(element, {
                        allowHTML: true,
                        theme: 'dark',
                        placement: 'top',
                        maxWidth: 350,
                        delay: [300, 100],
                        arrow: true,
                        animation: 'fade',
                        // Optimisation : cache le contenu
                        content: element.getAttribute('data-tippy-content'),
                        // Optimisation : lazy loading
                        onShow: (instance) => {
                            // Mise à jour du contenu si nécessaire
                            const currentContent = element.getAttribute('data-tippy-content');
                            if (instance.props.content !== currentContent) {
                                instance.setContent(currentContent);
                            }
                        }
                    });
                    
                    tippyInstancesCache.set(elementId, instance);
                    element.setAttribute('data-tippy-initialized', 'true');
                } catch (error) {
                    console.warn('Erreur lors de la création du tooltip:', error);
                }
            }
        }
        
        currentBatch++;
        if (end < elementsWithTooltips.length) {
            // Traiter le batch suivant de manière asynchrone
            requestAnimationFrame(processBatch);
        }
    }
    
    processBatch();
}

/**
 * Détruit seulement les tooltips spécifiques à une date/semaine
 * au lieu de tout détruire
 */
function destroySelectiveTooltips(date, weekNumber) {
    const selectorsToDestroy = [
        `[data-date="${date}"] [data-tippy-content]`,
        `[data-week="${weekNumber}"] [data-tippy-content]`
    ];
    
    selectorsToDestroy.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(element => {
            const elementId = element.dataset.elementId;
            if (tippyInstancesCache.has(elementId)) {
                const instance = tippyInstancesCache.get(elementId);
                instance.destroy();
                tippyInstancesCache.delete(elementId);
                element.removeAttribute('data-tippy-initialized');
            }
        });
    });
}

/**
 * Recharge seulement les tooltips d'une date/semaine spécifique
 */
function reloadSelectiveTooltips(date, weekNumber) {
    // Détruire seulement les tooltips concernés
    destroySelectiveTooltips(date, weekNumber);
    
    // Réinitialiser seulement les tooltips des éléments concernés
    setTimeout(() => {
        initOptimizedTooltips();
    }, 50);
}

/**
 * Méthode pour nettoyer tous les tooltips (pour le changement d'année)
 */
function destroyAllTooltipsOptimized() {
    tippyInstancesCache.forEach(instance => {
        try {
            instance.destroy();
        } catch (error) {
            console.warn('Erreur lors de la destruction du tooltip:', error);
        }
    });
    tippyInstancesCache.clear();
    
    // Retirer les attributs d'initialisation
    document.querySelectorAll('[data-tippy-initialized]')
        .forEach(el => el.removeAttribute('data-tippy-initialized'));
}

/**
 * Mise à jour du contenu d'un tooltip sans le recréer
 */
function updateTooltipContent(elementId, newContent) {
    if (tippyInstancesCache.has(elementId)) {
        const instance = tippyInstancesCache.get(elementId);
        instance.setContent(newContent);
    }
}

// Écouter les événements Livewire optimisés
document.addEventListener('livewire:init', () => {
    // Événement standard pour rechargement complet (changement d'année)
    Livewire.on('reload-tooltips', () => {
        destroyAllTooltipsOptimized();
        setTimeout(initOptimizedTooltips, 100);
    });
    
    // Nouvel événement pour rechargement sélectif
    Livewire.on('reload-tooltips-selective', (data) => {
        reloadSelectiveTooltips(data.date, data.weekNumber);
    });
    
    // Événement pour mise à jour du contenu seulement
    Livewire.on('update-tooltip-content', (data) => {
        updateTooltipContent(data.elementId, data.content);
    });
    
    // Initialisation initiale
    initOptimizedTooltips();
});

// Observer les mutations DOM pour les nouveaux éléments
const tooltipObserver = new MutationObserver((mutations) => {
    let hasNewTooltips = false;
    
    mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
            if (node.nodeType === Node.ELEMENT_NODE) {
                const hasTooltipContent = node.hasAttribute?.('data-tippy-content') || 
                                        node.querySelector?.('[data-tippy-content]');
                if (hasTooltipContent) {
                    hasNewTooltips = true;
                }
            }
        });
    });
    
    if (hasNewTooltips) {
        // Débounce pour éviter les appels multiples
        clearTimeout(window.tooltipInitTimeout);
        window.tooltipInitTimeout = setTimeout(initOptimizedTooltips, 150);
    }
});

// Démarrer l'observation une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
    tooltipObserver.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// Exporter les fonctions pour usage externe
window.TooltipManager = {
    init: initOptimizedTooltips,
    destroyAll: destroyAllTooltipsOptimized,
    destroySelective: destroySelectiveTooltips,
    reloadSelective: reloadSelectiveTooltips,
    updateContent: updateTooltipContent
};
