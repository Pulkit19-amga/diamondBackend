<?PHP
namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiamondVendor;
use Carbon\Carbon;

class DiamondVendorController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clarities = DiamondVendor::all();
            return response()->json($clarities);
        }
        return view('admin.DiamondMaster.Vendor.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        $validated['date_addded'] = Carbon::now();
        DiamondVendor::create($validated);
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        if (request()->ajax()) {
            $vendor = DiamondVendor::findOrFail($id); // Make sure Vendor model exists
            return response()->json($vendor);
        }
    }

    public function update(Request $request, $id)
    {
        $vendor = DiamondVendor::findOrFail($id);
        $validated = $request->validate($this->validationRules());
        $validated['date_updated'] = Carbon::now();
        $vendor->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        DiamondVendor::destroy($id);
        return response()->json(['success' => true]);
    }

    private function validationRules()
{
    return [
        'vendor_company_name'      => 'nullable|string|max:150',
        'vendor_name'              => 'nullable|string|max:255',
        'diamond_prefix'           => 'nullable|string|max:150',
        'vendor_email'             => 'nullable|email|max:100',
        'vendor_phone'             => 'nullable|string|max:20',
        'vendor_cell'              => 'nullable|string|max:25',
        'how_hear_about_us'        => 'nullable|string|max:150',
        'other_manufacturer_value' => 'nullable|string|max:250',
        'vendor_status'            => 'nullable|integer',
        'auto_status'              => 'nullable|boolean',
        'price_markup_type'        => 'nullable|integer',
        'price_markup_value'       => 'nullable|numeric',
        'fancy_price_markup_value' => 'nullable|numeric',
        'extra_markup'             => 'nullable|integer',
        'extra_markup_value'       => 'nullable|numeric',
        'fancy_extra_markup'       => 'nullable|integer',
        'fancy_extra_markup_value' => 'nullable|numeric',
        'delivery_days'            => 'nullable|string|max:20',
        'additional_shipping_day'  => 'nullable|string|max:20',
        'additional_rap_discount'  => 'nullable|string|max:50',
        'notification_email'       => 'nullable|email',
        'data_path'                => 'nullable|string',
        'media_path'               => 'nullable|string|max:50',
        'external_image'           => 'nullable|boolean',
        'external_image_path'      => 'nullable|string',
        'external_image_formula'   => 'nullable|string',
        'external_video'           => 'nullable|boolean',
        'external_video_path'      => 'nullable|string',
        'external_video_formula'   => 'nullable|string',
        'external_certificate'     => 'nullable|boolean',
        'external_certificate_path'=> 'nullable|string',
        'if_display_vendor_stock_no'=> 'nullable|boolean',
        'vm_diamond_type'          => 'nullable|string|max:150',
        'show_price'               => 'nullable|boolean',
        'duplicate_feed'           => 'nullable|boolean',
        'display_invtry_before_login'=> 'nullable|boolean',
        'vendor_product_group'     => 'nullable|string',
        'vendor_customer_group'    => 'nullable|string',
        'deleted'                  => 'nullable|boolean',
        'rank'                     => 'nullable|integer',
        'buying'                   => 'nullable|boolean',
        'buy_email'                => 'nullable|boolean',
        'price_grid'               => 'nullable|boolean',
        'display_certificate'      => 'nullable|boolean',
        'change_status_days'       => 'nullable|string|max:20',
        'diamond_size_from'        => 'nullable|numeric',
        'diamond_size_to'          => 'nullable|numeric',
        'allow_color'              => 'nullable|string',
        'location'                 => 'nullable|string',
        'offer_days'               => 'nullable|string|max:20',
        'keep_price_same_ab'       => 'nullable|boolean',
        'cc_fee'                   => 'nullable|boolean',
        'date_addded'              => 'nullable|date',
        'added_by'                 => 'nullable|integer',
        'date_updated'             => 'nullable|date',
        'update_by'                => 'nullable|integer',
    ];
}

}
