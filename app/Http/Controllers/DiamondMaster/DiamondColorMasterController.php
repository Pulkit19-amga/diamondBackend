<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiamondColor;
use Carbon\Carbon;

class DiamondColorMasterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(DiamondColor::all());
        }
        return view('admin.DiamondMaster.Color.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:250',
            'ALIAS' => 'nullable|string|max:250',
            'short_name' => 'nullable|string|max:150',
            'remark' => 'nullable|string|max:500',
            'display_in_front' => 'nullable|integer',
            'dc_is_fancy_color' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
        ]);

        $data['display_in_front'] = $request->has('display_in_front');
        $data['dc_is_fancy_color'] = $request->has('dc_is_fancy_color');
        $data['date_added'] = Carbon::now();

        DiamondColor::create($data);

        return response()->json(['status' => 'Diamond Color added successfully']);
    }

    public function show($id)
    {
        $color = DiamondColor::findOrFail($id);
        return response()->json($color);
    }

    public function update(Request $request, $id)
{
    $color = DiamondColor::findOrFail($id);

    $data = $request->validate([
        'name' => 'nullable|string|max:250',
        'ALIAS' => 'nullable|string|max:250',
        'short_name' => 'nullable|string|max:150',
        'remark' => 'nullable|string|max:500',
        'display_in_front' => 'nullable|integer',
        'dc_is_fancy_color' => 'nullable|integer',
        'sort_order' => 'nullable|integer',
    ]);
    
    $data['date_modify'] = Carbon::now();

    $color->update($data);

    return response()->json(['status' => 'Diamond Color updated successfully']);
}

    public function destroy($id)
    {
        $color = DiamondColor::findOrFail($id);
        $color->delete();

        return response()->json(['status' => 'Diamond Color deleted successfully']);
    }
}
