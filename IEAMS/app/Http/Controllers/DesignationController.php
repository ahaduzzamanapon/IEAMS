<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::all();
        return view('designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:designations,code',
        ]);

        Designation::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }

    public function destroy($id)
    {
        $desg = Designation::findOrFail($id);
        $desg->delete();

        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
