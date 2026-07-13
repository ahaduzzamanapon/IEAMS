<?php
namespace App\Http\Controllers;

use App\Models\Upazila;
use App\Models\District;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function index(Request $request)
    {
        $query = Upazila::with('district.division');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('bn_name', 'like', "%{$search}%")
                  ->orWhereHas('district', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }
        $upazilas = $query->orderBy('name')->paginate(10);
        $districts = District::orderBy('name')->get();
        return view('setups.upazilas', compact('upazilas', 'districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric|unique:upazilas,id,' . $request->id,
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'bn_name' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
        ]);

        try {
            Upazila::updateOrCreate(['id' => $request->id], $validated);
            return back()->with('success', 'Upazila saved successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $upazila = Upazila::findOrFail($id);
            $upazila->delete();
            return back()->with('success', 'Upazila deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getUpazilasByDistrict($districtId)
    {
        $upazilas = Upazila::where('district_id', $districtId)->orderBy('name')->get(['id', 'name', 'bn_name']);
        return response()->json($upazilas);
    }
}
