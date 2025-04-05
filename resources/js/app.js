import './bootstrap';

/**
 * Handles the start of a drag operation
 * @param {DragEvent} e - The drag event
 * @param {number|string} trainingId - The ID of the training being dragged
 */
function onDragStart(e, trainingId) {
    const isCopy = e.ctrlKey;
    // Store the training ID and copy status in the drag data
    e.dataTransfer.setData('text/plain', JSON.stringify({
        trainingId,
        isCopy
    }));
    
    // Apply visual feedback based on operation type (copy or move)
    if(isCopy) {
        e.currentTarget.classList.add('dragging-copy');
    } else {
        e.currentTarget.classList.add('opacity-50');
    }
}

/**
 * Handles the dragover event on drop targets
 * @param {DragEvent} e - The drag event
 */
function onDragOver(e) {
    e.preventDefault(); // Allow dropping
    e.currentTarget.classList.add('bg-blue-50', 'border-blue-300'); // Visual feedback for potential drop
}

/**
 * Handles the dragleave event on drop targets
 * @param {DragEvent} e - The drag event
 */
function onDragLeave(e) {
    // Remove visual feedback when dragging leaves the drop target
    e.currentTarget.classList.remove('bg-blue-50', 'border-blue-300', 'dragging-copy');
}

/**
 * Handles the drop event
 * @param {DragEvent} e - The drag event
 * @param {string} newDate - The new date for the training
 */
function onDrop(e, newDate) {
    e.preventDefault();
    // Extract data from the drag operation
    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
    const trainingId = data.trainingId;
    const isCopy = data.isCopy;
    
    // Remove visual feedback
    e.currentTarget.classList.remove('bg-blue-50', 'border-blue-300', 'dragging-copy');
    
    // Dispatch appropriate Livewire event based on operation type
    if(isCopy) {
        Livewire.dispatch('training-copied', {
            trainingId: parseInt(trainingId),
            newDate: newDate
        });
    } else {
        Livewire.dispatch('training-moved', {
            trainingId: parseInt(trainingId),
            newDate: newDate
        });
    }
}
