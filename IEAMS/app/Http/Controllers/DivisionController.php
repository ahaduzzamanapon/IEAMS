<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $query = Division::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('bn_name', 'like', "%{$search}%");
        }
        $divisions = $query->orderBy('name')->paginate(10);
        return view('setups.divisions', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric|unique:divisions,id,' . $request->id,
            'name' => 'required|string|max:255',
            'bn_name' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
        ]);

        try {
            Division::updateOrCreate(['id' => $request->id], $validated);
            return back()->with('success', 'Division saved successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $division = Division::findOrFail($id);
            $division->delete();
            return back()->with('success', 'Division deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
