// Tooltip
document.addEventListener('DOMContentLoaded', function() {
    tippy('.tooltip', {
        content: (reference) => {
            const tooltipText = reference.getAttribute('data-tooltip');
            return tooltipText.replace(/&#10;/g, '<br>');
        },
        allowHTML: true,
        placement: 'top',
        theme: 'light',
        arrow: true
    });
});

// Toast message
function showToast(message, type, timeout) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 left-24 border px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 z-20
                    ${type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto-remove after 3 seconds
    setTimeout(() => {
        setTimeout(() => toast.remove(), 300);
    }, timeout);
}