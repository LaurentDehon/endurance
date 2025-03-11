<div class="bg-white rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Create new training</h2>

    <x-input label="Date" type="date" wire:model="date" required/>
    
    <x-select label="Type d'entraînement" :options="$trainingTypes" option-label="name" option-value="id" wire:model="trainingTypeId" placeholder="Choisir un type" required/>

    <x-input label="Distance (km)" type="number" step="0.01" wire:model="distance"/>
    
    <x-input label="Durée (minutes)" type="number" wire:model="duration" />        
    
    <x-input label="Dénivelé (m)" type="number" wire:model="elevation"/>
    
    <x-textarea label="Notes" wire:model="notes" placeholder="Commentaires..."/>

    <div class="flex justify-end space-x-2 mt-6">
        <x-button label="Annuler" wire:click="closeModal()"/>
        <x-button primary label="Enregistrer" wire:click="save"/>
    </div>
</div>