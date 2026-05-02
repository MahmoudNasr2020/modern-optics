<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\LensBrand;
use App\glassLense;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LensBrandController extends Controller
{
    // ── List all brands ───────────────────────────────────────
    public function index()
    {
        $brands = LensBrand::withCount('lenses')->orderBy('name')->get();
        return view('dashboard.pages.lens-brands.index', compact('brands'));
    }

    // ── Show create form ──────────────────────────────────────
    public function create()
    {
        return view('dashboard.pages.lens-brands.create');
    }

    // ── Store new brand ───────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:lens_brands,name',
            'description' => 'nullable|string|max:500',
            'logo'        => 'nullable|image|max:2048',
        ], [
            'name.unique' => 'A brand with this name already exists.',
        ]);

        $data = [
            'name'        => trim($request->name),
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => true,
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('lens-brands', 'public');
        }

        $brand = LensBrand::create($data);

        return redirect()
            ->route('dashboard.lens-brands.show', $brand)
            ->with('success', 'Brand "'.$brand->name.'" created successfully.');
    }

    // ── Show brand + its lenses ───────────────────────────────
    public function show(LensBrand $lensBrand)
    {
        $lenses = $lensBrand->lenses()->orderBy('product_id')->paginate(30);
        return view('dashboard.pages.lens-brands.show', compact('lensBrand', 'lenses'));
    }

    // ── Edit brand ────────────────────────────────────────────
    public function edit(LensBrand $lensBrand)
    {
        return view('dashboard.pages.lens-brands.edit', compact('lensBrand'));
    }

    // ── Update brand ──────────────────────────────────────────
    public function update(Request $request, LensBrand $lensBrand)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('lens_brands', 'name')->ignore($lensBrand->id)],
            'description' => 'nullable|string|max:500',
            'logo'        => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data = [
            'name'        => trim($request->name),
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('lens-brands', 'public');
        }

        $lensBrand->update($data);

        return back()->with('success', 'Brand updated successfully.');
    }

    // ── Delete brand ──────────────────────────────────────────
    public function destroy(LensBrand $lensBrand)
    {
        // Set lens_brand_id to null on all related lenses before deleting
        $lensBrand->lenses()->update(['lens_brand_id' => null]);
        $lensBrand->delete();

        return redirect()
            ->route('dashboard.lens-brands.index')
            ->with('success', 'Brand deleted. Related lenses are now unassigned.');
    }

    // ── Excel import of lenses under a brand ─────────────────
    /*public function import(Request $request, LensBrand $lensBrand)
    {
        $request->validate(['import_data' => 'required|string']);

        $rows      = json_decode($request->import_data, true);
        $added     = 0;
        $skipped   = [];
        $errors    = [];

        // Get last product_id number to auto-generate next ones
        $lastId = glassLense::orderByDesc('id')->value('product_id');
        $nextNum = is_numeric($lastId) ? ((int)$lastId + 1) : 90001;

        foreach ($rows as $i => $row) {
            $rowNum = $i + 1;

            // Validate required fields
            if (empty($row['description'])) {
                $errors[] = "Row {$rowNum}: Missing description.";
                continue;
            }

            // Check duplicate: same brand + same product_id
            $productId = !empty($row['product_id']) ? trim($row['product_id']) : (string)$nextNum++;

            $exists = glassLense::where('product_id', $productId)->exists();
            if ($exists) {
                $skipped[] = "Row {$rowNum}: Product ID '{$productId}' already exists — skipped.";
                continue;
            }

            glassLense::create([
                'product_id'       => $productId,
                'lens_brand_id'    => $lensBrand->id,
                'brand'            => $lensBrand->name,    // keep brand string in sync
                'description'      => trim($row['description']),
                'frame_type'       => $row['frame_type']       ?? '',
                'lense_type'       => $row['lense_type']       ?? '',
                'lense_production' => $row['lense_production']  ?? 'Stock',
                'index'            => $row['index']            ?? '',
                'life_style'       => $row['life_style']       ?? '',
                'customer_activity'=> $row['customer_activity'] ?? '',
                'lense_tech'       => $row['lense_tech']       ?? 'Basic',
                'price'            => (float)($row['price']         ?? 0),
                'retail_price'     => (float)($row['retail_price']  ?? 0),
                'amount'           => (int)  ($row['amount']        ?? 0),
            ]);

            $added++;
        }

        $msg = "Successfully imported {$added} lens(es) under '{$lensBrand->name}'.";
        if (!empty($skipped)) session()->flash('import_skipped', $skipped);
        if (!empty($errors))  session()->flash('import_errors',  $errors);

        return redirect()
            ->route('dashboard.lens-brands.show', $lensBrand)
            ->with('success', $msg);
    }*/

    public function import(Request $request, LensBrand $lensBrand)
    {
        $request->validate(['import_data' => 'required|string']);

        $rows       = json_decode($request->import_data, true);
        $added      = 0;
        $skipped    = [];
        $errors     = [];
        $seenInFile = []; // track IDs within this import batch to catch in-file duplicates

        $lastId  = glassLense::orderByDesc('id')->value('product_id');
        $nextNum = is_numeric($lastId) ? ((int)$lastId + 1) : 90001;

        foreach ($rows as $i => $row) {
            $rowNum = $i + 1;

            if (empty($row['description'])) {
                $errors[] = "Row {$rowNum}: Missing description.";
                continue;
            }

            $productId = !empty($row['product_id']) ? trim($row['product_id']) : (string)$nextNum++;

            // 1) Duplicate within the file itself
            if (isset($seenInFile[$productId])) {
                $skipped[] = "Row {$rowNum}: Product ID '{$productId}' is duplicated in the file (first seen at row {$seenInFile[$productId]}) — skipped.";
                continue;
            }

            // 2) Duplicate in the database
            if (glassLense::where('product_id', $productId)->exists()) {
                $skipped[] = "Row {$rowNum}: Product ID '{$productId}' already exists in the database — skipped.";
                continue;
            }

            $seenInFile[$productId] = $rowNum;

            glassLense::create([
                'product_id'        => $productId,
                'lens_brand_id'     => $lensBrand->id,
                'brand'             => $lensBrand->name,
                'description'       => trim($row['description']),
                'frame_type'        => $row['frame_type']        ?? '',
                'lense_type'        => $row['lense_type']        ?? '',
                'lense_production'  => $row['lense_production']  ?? 'Stock',
                'index'             => $row['index']             ?? '',
                'life_style'        => $row['life_style']        ?? '',
                'customer_activity' => $row['customer_activity'] ?? '',
                'lense_tech'        => $row['lense_tech']        ?? 'Basic',
                'price'             => (float)($row['price']        ?? 0),
                'retail_price'      => (float)($row['retail_price'] ?? 0),
                'amount'            => (int)  ($row['amount']       ?? 0),
            ]);

            $added++;
        }

        $msg = $added > 0
            ? "Successfully imported {$added} lens(es) under '{$lensBrand->name}'."
            : "No lenses were imported. Check the details below.";

        session()->flash('import_added',   $added);
        session()->flash('import_skipped', $skipped);
        session()->flash('import_errors',  $errors);

        return redirect()
            ->route('dashboard.lens-brands.show', $lensBrand)
            ->with('success', $msg);
    }

    // ── Download lens import template ─────────────────────────
    public function template()
    {
        $path = public_path('templates/lens_import_template.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template file not found.');
        }

        // Clear ALL output buffers — same approach as stock template download
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="lens_import_template.xlsx"');
        header('Content-Length: ' . filesize($path));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($path);
        exit;
    }


// ── AJAX: check if brand name exists ──────────────────────
    public function checkName(Request $request)
    {
        $exists = LensBrand::where('name', trim($request->name))->exists();
        return response()->json(['exists' => $exists]);
    }

}
