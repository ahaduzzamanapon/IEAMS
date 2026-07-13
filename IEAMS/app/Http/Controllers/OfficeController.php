<?php
namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of offices.
     */
    public function index()
    {
        $offices = Office::withCount('branches')->get();
        return view('offices.index', compact('offices'));
    }

    /**
     * Store a newly created office in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:offices,code',
        ]);

        Office::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('offices.index')->with('success', 'Office created successfully.');
    }

    /**
     * Remove the specified office from storage.
     */
    public function destroy($id)
    {
        $office = Office::findOrFail($id);
        $office->delete();

        return redirect()->route('offices.index')->with('success', 'Office deleted successfully.');
    }
}
