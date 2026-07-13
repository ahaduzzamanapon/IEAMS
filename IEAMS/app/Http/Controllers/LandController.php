<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Land;
use Illuminate\Http\Request;

class LandController extends Controller
{
    public function index(Request $request)
    {
        $query = Land::with('project')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('seller_information', 'like', "%{$search}%")
                  ->orWhere('deed_number', 'like', "%{$search}%")
                  ->orWhere('khatian_number', 'like', "%{$search}%")
                  ->orWhere('dag_number', 'like', "%{$search}%")
                  ->orWhereHas('project', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('project_id_code', 'like', "%{$search}%");
                  });
            });
        }

        $lands = $query->paginate(10)->withQueryString();
        return view('property.lands.index', compact('lands'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('property.lands.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'purchase_date' => 'required|date',
            'purchase_value' => 'required|numeric|min:0',
            'seller_information' => 'required|string|max:255',
            'deed_number' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'khatian_number' => 'required|string|max:255',
            'dag_number' => 'required|string|max:255',
            'land_amount' => 'required|numeric|min:0',
            'land_classification' => 'required|string|max:255',
            'land_map' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('land_map')) {
            $path = $request->file('land_map')->store('land_maps', 'public');
            $validated['land_map_path'] = $path;
        }

        try {
            Land::create($validated);
            return redirect()->route('property.lands.index')->with('success', 'Land registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $land = Land::findOrFail($id);
        $projects = Project::all();
        return view('property.lands.edit', compact('land', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $land = Land::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'purchase_date' => 'required|date',
            'purchase_value' => 'required|numeric|min:0',
            'seller_information' => 'required|string|max:255',
            'deed_number' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'khatian_number' => 'required|string|max:255',
            'dag_number' => 'required|string|max:255',
            'land_amount' => 'required|numeric|min:0',
            'land_classification' => 'required|string|max:255',
            'land_map' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('land_map')) {
            // Delete old file if exists
            if ($land->land_map_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($land->land_map_path);
            }
            $path = $request->file('land_map')->store('land_maps', 'public');
            $validated['land_map_path'] = $path;
        }

        try {
            $land->update($validated);
            return redirect()->route('property.lands.index')->with('success', 'Land details updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $land = Land::findOrFail($id);
        try {
            $land->delete();
            return redirect()->route('property.lands.index')->with('success', 'Land details deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
