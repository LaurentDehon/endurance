<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Record the visit
        $this->recordVisit($request);
        
        return view('home');
    }
    
    private function recordVisit(Request $request)
    {
        // Get the visitor's IP address
        $ipAddress = $request->ip();
        
        // Get country from IP address using a geolocation service
        $country = $this->getCountryFromIp($ipAddress);
        
        // Create the visit record
        Visit::create([
            'ip_address' => $ipAddress,
            'country' => $country,
            'user_agent' => $request->userAgent(),
            'visited_at' => Carbon::now(),
        ]);
    }
    
    private function getCountryFromIp($ip)
    {
        // Use a free geolocation service to get country from IP
        // For local/private IPs, we'll return 'Local' as the country
        if (in_array($ip, ['127.0.0.1', 'localhost', '::1']) || 
            preg_match('/^(192\.168|10\.|172\.(1[6-9]|2[0-9]|3[0-1]))/', $ip)) {
            return 'Local Network';
        }
        
        try {
            // Using a free IP geolocation API (no API key required)
            $response = Http::get("https://ipapi.co/{$ip}/json/");
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['country_name'] ?? 'Unknown';
            }
        } catch (\Exception $e) {
            // Log the error but continue execution
            Log::error("IP Geolocation Error: " . $e->getMessage());
        }
        
        return 'Unknown';
    }
}
