<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\LensLab;
use Illuminate\Http\Request;

class LensLabController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:lens_labs,name',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        LensLab::create([
            'name'  => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'notes' => $request->notes,
        ]);

        session()->flash('success', 'Lab added successfully!');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $lab = LensLab::findOrFail($id);
        $lab->update(['is_active' => 0]);
        session()->flash('success', 'Lab deactivated.');
        return redirect()->back();
    }
}
