<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use TallStackUi\Traits\Interactions;

class StravaController extends Controller
{
    use Interactions; 

    public function __construct(protected StravaAuthService $authService) {}

    public function redirect()
    {
        return redirect($this->authService->getRedirectUrl());
    }

    public function handleCallback(Request $request)
    {
        try {
            $this->authService->handleAuthorizationCode($request->code);
            $this->toast()->success('Strava connected')->send();

        } catch (\Exception $e) {
            $this->toast()->error('Connection failed : ' . $e->getMessage())->send();
        }

        return redirect()->route('calendar.index');
    }

    public function showConnect()
    {
        return view('strava.connect');
    }
}