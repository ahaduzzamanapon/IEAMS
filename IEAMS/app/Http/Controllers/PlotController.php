<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Plot;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    public function index(Request $request)
    {
        $query = Plot::with('project')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('plot_number', 'like', "%{$search}%")
                  ->orWhere('plot_name', 'like', "%{$search}%")
                  ->orWhereHas('project', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $plots = $query->paginate(10)->withQueryString();
        return view('property.plots.index', compact('plots'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('property.plots.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'land_id' => 'nullable|exists:lands,id',
            'plot_number' => 'required|string|max:255',
            'plot_name' => 'nullable|string|max:255',
            'plot_area' => 'required|numeric|min:0.01',
            'status' => 'required|in:vacant,sold,leased',
        ]);

        try {
            Plot::create($validated);
            return redirect()->route('property.plots.index')->with('success', 'Plot registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $plot = Plot::findOrFail($id);
        $projects = Project::all();
        $lands = \App\Models\Land::where('project_id', $plot->project_id)->get();
        return view('property.plots.edit', compact('plot', 'projects', 'lands'));
    }

    public function update(Request $request, $id)
    {
        $plot = Plot::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'land_id' => 'nullable|exists:lands,id',
            'plot_number' => 'required|string|max:255',
            'plot_name' => 'nullable|string|max:255',
            'plot_area' => 'required|numeric|min:0.01',
            'status' => 'required|in:vacant,sold,leased',
        ]);

        try {
            $plot->update($validated);
            return redirect()->route('property.plots.index')->with('success', 'Plot details updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $plot = Plot::findOrFail($id);
        try {
            $plot->delete();
            return redirect()->route('property.plots.index')->with('success', 'Plot deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
