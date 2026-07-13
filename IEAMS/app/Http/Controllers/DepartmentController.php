<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('branch.office')->get();
        $branches = Branch::with('office')->get();
        return view('departments.index', compact('departments', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        $exists = Department::where('branch_id', $request->branch_id)
            ->where('code', strtoupper($request->code))
            ->exists();

        if ($exists) {
            return back()->withErrors(['code' => 'This department code is already registered for this branch.'])->withInput();
        }

        Department::create([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function destroy($id)
    {
        $dept = Department::findOrFail($id);
        $dept->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function getDepartmentsByBranch($branchId)
    {
        $depts = Department::where('branch_id', $branchId)->get(['id', 'name', 'code']);
        return response()->json($depts);
    }
}
