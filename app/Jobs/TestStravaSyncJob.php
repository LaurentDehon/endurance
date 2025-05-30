<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\StravaSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestStravaSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function handle(StravaSyncService $syncService): void
    {
        $user = User::find($this->userId);
        
        if (!$user) {
            Log::error("User {$this->userId} not found for test sync");
            return;
        }

        Log::info("Starting test Strava sync for user {$user->id}");
        
        // Test creating a Day without Auth context (simulates queue job environment)
        try {
            $day = \App\Models\Day::findByDateOrCreate('2024-01-01', $user->id);
            Log::info("Successfully created/found day with ID: {$day->id}");
        } catch (\Exception $e) {
            Log::error("Failed to create day: " . $e->getMessage());
            throw $e;
        }

        // Now test the actual sync service
        try {
            $result = $syncService->sync($user);
            Log::info("Sync result: " . json_encode($result));
        } catch (\Exception $e) {
            Log::error("Sync failed: " . $e->getMessage());
            throw $e;
        }
    }
}
