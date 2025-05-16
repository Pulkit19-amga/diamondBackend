<?php
namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use App\Models\DiamondMaster;
use App\Models\DiamondVendor;
use App\Models\DiamondLab;
use App\Models\DiamondShape;
use App\Models\DiamondColor;
use App\Models\DiamondClarityMaster;
use Illuminate\Http\Request;
 
class DiamondMasterControllerNew extends Controller
{
    public function index()
    {
        return view('admin.DiamondMaster.master.index', [
            'vendors'               => DiamondVendor::pluck('vendor_name','vendorid'),
            'certificate_companies' => DiamondLab::pluck('dl_name','dl_id'),
            'shapes'                => DiamondShape::pluck('name','id'),
            'colors'                => DiamondColor::pluck('name','id'),
            'clarities'             => DiamondClarityMaster::pluck('name','id'),
        ]);
    }

    public function data()
    {
        $records = DiamondMaster::with([
            'vendor','shape','color','clarity','certificateCompany'
        ])->orderBy('diamondid','desc')->take(20)->get();
        //dd($records);
        return response()->json($records);
    }

    public function store(Request $req)
    {
        $v = $req->validate([
            'diamond_type'        => 'required|in:1,2',
            'quantity'            => 'required|integer|min:1',
            'vendor_id'           => 'required|exists:vendor_master,vendorid',
            'shape'               => 'required|integer',
            'color'               => 'required|integer',
            'clarity'             => 'required|integer',
            'certificate_company' => 'required|exists:diamond_lab_master,dl_id',
            'carat_weight'        => 'required|numeric',
            'price_per_carat'     => 'nullable|numeric',
            'vendor_price'        => 'nullable|numeric',
            'certificate_number'  => 'nullable|string',
            'certificate_date'    => 'nullable|date',
            'availability'        => 'nullable|in:0,1,2',
            'is_superdeal'        => 'nullable|boolean',
            'status'              => 'nullable|boolean',
            'date_added'          => 'nullable|date',
            'date_updated'        => 'nullable|date',
        ]);

        DiamondMaster::create($v);
        return response()->json(['message'=>'Saved'],200);
    }

    public function edit($id)
    {
        return response()->json(DiamondMaster::findOrFail($id));
    }

    public function update(Request $req, $id)
    {
        $v = $req->validate([ /* same rules as store */ ]);
        DiamondMaster::findOrFail($id)->update($v);
        return response()->json(['message'=>'Updated'],200);
    }

    public function destroy($id)
    {
        DiamondMaster::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted'],200);
    }

    public function updateStatus(Request $req, $id)
    {
        $dm = DiamondMaster::findOrFail($id);
        $dm->status = $req->status;
        $dm->save();
        return response()->json(['message'=>'Status updated'],200);
    }
}
