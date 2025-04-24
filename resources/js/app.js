import './bootstrap';
import Alpine from 'alpinejs';
import '../css/app.css';

// Assurons-nous d'attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {
    // Rendre Alpine disponible globalement
    window.Alpine = Alpine;
    
    // Attendre un court instant pour s'assurer que Livewire est initialisé
    setTimeout(() => {
        // Démarrer Alpine après Livewire
        Alpine.start();
        console.log('Alpine.js démarré avec succès');
    }, 10);
});