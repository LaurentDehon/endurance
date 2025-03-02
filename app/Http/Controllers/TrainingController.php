<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Training;
use App\Models\TrainingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function show($id)
    {
        $training = Training::with('trainingType')->findOrFail($id);
        $trainingTypes = TrainingType::all();

        return view('trainings.show', compact('training', 'trainingTypes'));
    }

    public function create(string $date)
    {
        $trainingTypes = TrainingType::all();  
        $date = Carbon::parse($date);

        return view('trainings.create', compact('trainingTypes', 'date'));
    }    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'distance' => 'nullable|numeric|min:0',
            'hours' => 'nullable|integer|min:0',
            'minutes' => 'nullable|integer|min:0|max:59',
            'elevation' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'type' => 'required|exists:training_types,id',
        ]);

        $duration = ($validated['hours'] ?? 0) * 60 + ($validated['minutes'] ?? 0);

        Training::create([
            'user_id' => Auth::id(),
            'date' => $validated['date'],
            'distance' => $validated['distance'],
            'duration' => $duration,
            'elevation' => $validated['elevation'],
            'notes' => $validated['notes'],
            'training_type_id' => $validated['type'],
        ]);

        return redirect()->route('calendar.index')->with('success', 'Training successfully added');
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'date' => 'sometimes|date',
            'distance' => 'sometimes|nullable|numeric|min:0',
            'hours' => 'sometimes|nullable|integer|min:0',
            'minutes' => 'sometimes|nullable|integer|min:0|max:59',
            'elevation' => 'sometimes|nullable|integer|min:0',
            'notes' => 'sometimes|nullable|string',
            'training_type_id' => 'sometimes|exists:training_types,id',
        ]);

        if (isset($validated['hours']) || isset($validated['minutes'])) {
            $validated['duration'] = ($validated['hours'] ?? 0) * 60 + ($validated['minutes'] ?? 0);
        }

        unset($validated['hours'], $validated['minutes']);

        $training->update($validated);

        return redirect()->route('calendar.index')->with('success', 'Training updated successfully');
    }

    public function updateDate(Request $request, Training $training)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date'
            ]);

            $training->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Date updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createRoutine()
    {
        $trainingTypes = TrainingType::all();

        return view('trainings.create-routine', compact('trainingTypes'));
    }

    public function storeRoutine(Request $request)
    {
        $validated = $request->validate([
            'recurrence_type' => 'required|in:daily,weekly,monthly',
            'interval' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'days' => 'array|required_if:recurrence_type,weekly',
            'days.*' => 'integer|between:0,6',
            // Validation des champs existants
            'distance' => 'nullable|numeric|min:0',
            'hours' => 'nullable|integer|min:0',
            'minutes' => 'nullable|integer|min:0|max:59',
            'elevation' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'type' => 'required|exists:training_types,id',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $validated['end_date'] ? Carbon::parse($validated['end_date']) : null;

        $dates = $this->generateRecurringDates(
            $startDate,
            $endDate,
            $validated['recurrence_type'],
            $validated['interval'],
            $validated['days'] ?? []
        );

        $duration = ($validated['hours'] ?? 0) * 60 + ($validated['minutes'] ?? 0);

        foreach ($dates as $date) {
            Training::create([
                'user_id' => Auth::id(),
                'date' => $date->format('Y-m-d'),
                'distance' => $validated['distance'],
                'duration' => $duration,
                'elevation' => $validated['elevation'],
                'training_type_id' => $validated['type'],
            ]);
        }

        return redirect()->route('calendar.index')->with('success', count($dates) . ' training sessions created');
    }

    private function generateRecurringDates(Carbon $start, ?Carbon $end, string $type, int $interval, array $days = []): array
    {
        $dates = [];

        if ($type === 'daily') {
            for ($date = $start->copy(); !$end || $date <= $end; $date->addDays($interval)) {
                $dates[] = $date->copy();
            }
        }

        if ($type === 'weekly') {
            $date = $start->copy();

            while (!$end || $date <= $end) {
                foreach ($days as $day) {
                    $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
                    $trainingDate = $weekStart->addDays((int)$day);

                    if ($trainingDate >= $start && (!$end || $trainingDate <= $end)) {
                        $dates[] = $trainingDate->copy();
                    }
                }

                $date->addWeeks($interval);
            }
        }

        if ($type === 'monthly') {
            $startDay = $start->day;
            $date = $start->copy();

            while (!$end || $date <= $end) {
                $lastDayOfMonth = $date->copy()->endOfMonth()->day;
                $dayToSet = min($startDay, $lastDayOfMonth);

                $dates[] = $date->copy()->day($dayToSet);
                $date->addMonths($interval);
            }
        }

        return $dates;
    }

    public function destroy(int $id)
    {
        $training = Training::findOrFail($id);
        $training->delete();

        return redirect()->route('calendar.index')->with('success', 'Training successfully deleted');
    }

    public function destroyAll()
    {
        Training::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'All trainings deleted successfully');
    }
}
