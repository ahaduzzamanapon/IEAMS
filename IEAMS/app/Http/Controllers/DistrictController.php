<?php
namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $query = District::with('division');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('bn_name', 'like', "%{$search}%")
                  ->orWhereHas('division', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }
        $districts = $query->orderBy('name')->paginate(10);
        $divisions = Division::orderBy('name')->get();
        return view('setups.districts', compact('districts', 'divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric|unique:districts,id,' . $request->id,
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255',
            'bn_name' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
        ]);

        try {
            District::updateOrCreate(['id' => $request->id], $validated);
            return back()->with('success', 'District saved successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $district = District::findOrFail($id);
            $district->delete();
            return back()->with('success', 'District deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getDistrictsByDivision($divisionId)
    {
        $districts = District::where('division_id', $divisionId)->orderBy('name')->get(['id', 'name', 'bn_name']);
        return response()->json($districts);
    }
}
