<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Apartment::with('floor.building.plot.project')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('apartment_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('parking', 'like', "%{$search}%")
                  ->orWhereHas('floor.building', function($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%")
                        ->orWhereHas('plot.project', function($pq) use ($search) {
                            $pq->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $apartments = $query->paginate(10)->withQueryString();
        return view('property.apartments.index', compact('apartments'));
    }

    public function create()
    {
        $projects = \App\Models\Project::all();
        return view('property.apartments.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'apartment_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'size' => 'required|numeric|min:0.01',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'balcony' => 'nullable|integer|min:0',
            'parking' => 'nullable|string|max:255',
            'utility_connection' => 'nullable|boolean',
            'orientation' => 'required|string|max:255',
            'status' => 'required|in:vacant,reserved,booked,allocated,rented,sold,under_maintenance,cancelled',
        ]);

        $validated['utility_connection'] = $request->has('utility_connection');

        try {
            Apartment::create($validated);
            return redirect()->route('property.apartments.index')->with('success', 'Apartment registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $apartment = Apartment::with('floor.building.plot.land.project')->findOrFail($id);
        $projects = \App\Models\Project::all();

        $selectedFloor = $apartment->floor;
        $selectedBuilding = $selectedFloor ? $selectedFloor->building : null;
        $selectedPlot = $selectedBuilding ? $selectedBuilding->plot : null;
        $selectedLand = $selectedPlot ? $selectedPlot->land : null;
        $selectedProject = $selectedLand ? $selectedLand->project : ($selectedPlot ? $selectedPlot->project : null);

        $lands = $selectedProject ? \App\Models\Land::where('project_id', $selectedProject->id)->get() : collect();
        $plots = collect();
        if ($selectedLand) {
            $plots = \App\Models\Plot::where('land_id', $selectedLand->id)->get();
        } elseif ($selectedProject) {
            $plots = \App\Models\Plot::where('project_id', $selectedProject->id)->get();
        }

        $buildings = $selectedPlot ? \App\Models\Building::where('plot_id', $selectedPlot->id)->get() : collect();
        $floors = $selectedBuilding ? \App\Models\Floor::where('building_id', $selectedBuilding->id)->get() : collect();

        return view('property.apartments.edit', compact(
            'apartment', 'projects', 'lands', 'plots', 'buildings', 'floors',
            'selectedProject', 'selectedLand', 'selectedPlot', 'selectedBuilding', 'selectedFloor'
        ));
    }

    public function update(Request $request, $id)
    {
        $apartment = Apartment::findOrFail($id);

        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'apartment_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'size' => 'required|numeric|min:0.01',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'balcony' => 'nullable|integer|min:0',
            'parking' => 'nullable|string|max:255',
            'utility_connection' => 'nullable|boolean',
            'orientation' => 'required|string|max:255',
            'status' => 'required|in:vacant,reserved,booked,allocated,rented,sold,under_maintenance,cancelled',
        ]);

        $validated['utility_connection'] = $request->has('utility_connection');

        try {
            $apartment->update($validated);
            return redirect()->route('property.apartments.index')->with('success', 'Apartment details updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $apartment = Apartment::findOrFail($id);
        try {
            $apartment->delete();
            return redirect()->route('property.apartments.index')->with('success', 'Apartment deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
