<?php
namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of branches.
     */
    public function index()
    {
        $branches = Branch::with('office')->get();
        $offices = Office::all();
        return view('branches.index', compact('branches', 'offices'));
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'office_id' => 'required|exists:offices,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        // Check uniqueness of code for the specific office
        $exists = Branch::where('office_id', $request->office_id)
            ->where('code', strtoupper($request->code))
            ->exists();

        if ($exists) {
            return back()->withErrors(['code' => 'This branch code is already registered for this office.'])->withInput();
        }

        Branch::create([
            'office_id' => $request->office_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }

    /**
     * Get branches by office (JSON endpoint for dependent dropdowns).
     */
    public function getBranchesByOffice($officeId)
    {
        $branches = Branch::where('office_id', $officeId)->get(['id', 'name', 'code']);
        return response()->json($branches);
    }
}
