<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class Profile extends Component
{
    // Propriétés pour la mise à jour du profil
    public $name;
    public $email;
    
    // Propriétés pour la modification du mot de passe
    public $current_password;
    public $password;
    public $password_confirmation;
    
    // Propriétés pour la suppression du compte
    public $delete_password;
    
    // Règles de validation
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
        ];
    }
    
    /**
     * Initialisation des propriétés avec les données de l'utilisateur connecté
     */
    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }
    
    /**
     * Mise à jour du profil utilisateur
     */
    public function updateProfile()
    {
        $this->validate();
        
        $user = Auth::user();
        
        if ($this->email !== $user->email) {
            // Si l'email a changé, réinitialiser le statut de vérification d'email si applicable
            if (method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
                $user->email_verified_at = null;
            }
        }
        
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        
        $this->dispatch('toast', __('profile.personal_information.profile_updated'), 'success');
    }
    
    /**
     * Mise à jour du mot de passe
     */
    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($this->password),
        ]);
        
        $this->reset(['current_password', 'password', 'password_confirmation']);
        
        $this->dispatch('toast', __('profile.security.password_updated'), 'success');
    }
    
    /**
     * Demande de confirmation pour supprimer le compte
     */
    public function confirmAccountDeletion()
    {
        $this->validate([
            'delete_password' => ['required', 'string'],
        ]);
        
        if (! Hash::check($this->delete_password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'delete_password' => [__('auth.password')],
            ]);
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => __('profile.danger_zone.confirm_delete'),
            'message' => __('profile.danger_zone.delete_account_confirmation'),
            'confirmButtonText' => __('profile.danger_zone.delete_account'),
            'cancelButtonText' => __('profile.cancel'),
            'confirmAction' => 'deleteAccount',
            'icon' => 'trash-alt',
            'iconColor' => 'red'
        ]);
    }
    
    /**
     * Suppression du compte utilisateur
     */
    public function deleteAccount()
    {
        $user = Auth::user();
        
        Auth::logout();
        
        // Supprimer la session et le cookie "remember me"
        session()->invalidate();
        session()->regenerateToken();
        
        $user->delete();
        
        return redirect()->route('home');
    }
    
    public function render()
    {
        return view('livewire.profile');
    }
}
