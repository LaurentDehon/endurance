<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        return view('settings.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'notification_email' => ['boolean', 'nullable'],
            'notification_app' => ['boolean', 'nullable'],
            'theme' => ['string', 'in:light,dark,system', 'nullable'],
            'language' => ['string', 'in:fr,en', 'nullable'],
            'timezone' => ['string', 'nullable'],
        ]);

        $user = $request->user();
        
        // Enregistrer la langue actuelle
        $oldLanguage = $user->settings['language'] ?? config('app.locale');
        
        // Merge with existing settings
        $user->setSettings(array_merge($user->getSettings() ?? [], $validated));
        $user->save();

        // Si la langue a changé, effacer le cache des traductions et actualiser la locale
        if (isset($validated['language']) && $validated['language'] !== $oldLanguage) {
            App::setLocale($validated['language']);
            
            // Mise à jour de la session avec la nouvelle langue
            session(['locale' => $validated['language']]);
            
            Artisan::call('cache:clear');
        }

        return Redirect::route('settings.index')
            ->with('toast', [
                'message' => __('settings.messages.updated_successfully'),
                'type' => 'success'
            ]);
    }
    
    /**
     * Met à jour le fuseau horaire de l'utilisateur automatiquement
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTimezone(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string'
        ]);
        
        $user = $request->user();
        $timezone = $request->input('timezone');
        
        // Vérifier si le fuseau horaire est valide
        try {
            new \DateTimeZone($timezone);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid timezone'], 400);
        }
        
        // Mettre à jour les paramètres de l'utilisateur
        $user->setSettings(array_merge($user->getSettings() ?? [], ['timezone' => $timezone]));
        $user->save();
        
        return response()->json(['success' => true]);
    }
}
