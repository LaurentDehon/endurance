<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Illuminate\Http\Request;

class StravaController extends Controller
{
    public function __construct(
        protected StravaAuthService $authService
    ) {}

    public function redirect()
    {
        return redirect($this->authService->getRedirectUrl());
    }

    public function handleCallback(Request $request)
    {
        try {
        $this->authService->handleAuthorizationCode($request->code);

        session()->flash('wireui_notify', [
            'title' => 'Success',
            'description' => 'Strava connected',
            'icon' => 'success',
        ]);

    } catch (\Exception $e) {
        session()->flash('wireui_notify', [
            'title' => 'Error',
            'description' => 'Connection failed',
            'icon' => 'error',
        ]);
    }

    return redirect()->route('calendar.index');
    }

    public function showConnect()
    {
        return view('strava.connect');
    }
}