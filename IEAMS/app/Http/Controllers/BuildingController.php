<?php
namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $query = Building::with('plot.project')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%")
                  ->orWhereHas('plot', function($pq) use ($search) {
                      $pq->where('plot_number', 'like', "%{$search}%")
                        ->orWhereHas('project', function($prq) use ($search) {
                            $prq->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('construction_status', $request->status);
        }

        $buildings = $query->paginate(10)->withQueryString();
        return view('property.buildings.index', compact('buildings'));
    }

    public function create()
    {
        $projects = \App\Models\Project::all();
        return view('property.buildings.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'footprint_area' => 'required|numeric|min:0.01',
            'total_floor' => 'required|integer|min:1',
            'has_lift' => 'nullable|boolean',
            'has_parking' => 'nullable|boolean',
            'construction_status' => 'required|in:planned,under_construction,completed',
        ]);

        $validated['has_lift'] = $request->has('has_lift');
        $validated['has_parking'] = $request->has('has_parking');

        try {
            Building::create($validated);
            return redirect()->route('property.buildings.index')->with('success', 'Building registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $building = Building::with('plot.land.project')->findOrFail($id);
        $projects = \App\Models\Project::all();
        
        $selectedPlot = $building->plot;
        $selectedLand = $selectedPlot ? $selectedPlot->land : null;
        $selectedProject = $selectedLand ? $selectedLand->project : ($selectedPlot ? $selectedPlot->project : null);

        $lands = $selectedProject ? \App\Models\Land::where('project_id', $selectedProject->id)->get() : collect();
        $plots = collect();
        if ($selectedLand) {
            $plots = \App\Models\Plot::where('land_id', $selectedLand->id)->get();
        } elseif ($selectedProject) {
            $plots = \App\Models\Plot::where('project_id', $selectedProject->id)->get();
        }

        return view('property.buildings.edit', compact('building', 'projects', 'lands', 'plots', 'selectedProject', 'selectedLand'));
    }

    public function update(Request $request, $id)
    {
        $building = Building::findOrFail($id);

        $validated = $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'footprint_area' => 'required|numeric|min:0.01',
            'total_floor' => 'required|integer|min:1',
            'has_lift' => 'nullable|boolean',
            'has_parking' => 'nullable|boolean',
            'construction_status' => 'required|in:planned,under_construction,completed',
        ]);

        $validated['has_lift'] = $request->has('has_lift');
        $validated['has_parking'] = $request->has('has_parking');

        try {
            $building->update($validated);
            return redirect()->route('property.buildings.index')->with('success', 'Building details updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $building = Building::findOrFail($id);
        try {
            $building->delete();
            return redirect()->route('property.buildings.index')->with('success', 'Building deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
