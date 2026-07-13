<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index(Request $request)
    {
        $query = Floor::with('building.plot.project')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('floor_number', 'like', "%{$search}%")
                  ->orWhere('floor_name', 'like', "%{$search}%")
                  ->orWhereHas('building', function($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%")
                        ->orWhere('number', 'like', "%{$search}%")
                        ->orWhereHas('plot.project', function($pq) use ($search) {
                            $pq->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        $floors = $query->paginate(10)->withQueryString();
        return view('property.floors.index', compact('floors'));
    }

    public function create()
    {
        $projects = \App\Models\Project::all();
        return view('property.floors.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_number' => 'required|string|max:255',
            'floor_name' => 'nullable|string|max:255',
            'total_apartment' => 'required|integer|min:0',
        ]);

        try {
            Floor::create($validated);
            return redirect()->route('property.floors.index')->with('success', 'Floor registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $floor = Floor::with('building.plot.land.project')->findOrFail($id);
        $projects = \App\Models\Project::all();

        $selectedBuilding = $floor->building;
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

        return view('property.floors.edit', compact('floor', 'projects', 'lands', 'plots', 'buildings', 'selectedProject', 'selectedLand', 'selectedPlot'));
    }

    public function update(Request $request, $id)
    {
        $floor = Floor::findOrFail($id);

        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_number' => 'required|string|max:255',
            'floor_name' => 'nullable|string|max:255',
            'total_apartment' => 'required|integer|min:0',
        ]);

        try {
            $floor->update($validated);
            return redirect()->route('property.floors.index')->with('success', 'Floor details updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $floor = Floor::findOrFail($id);
        try {
            $floor->delete();
            return redirect()->route('property.floors.index')->with('success', 'Floor deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
