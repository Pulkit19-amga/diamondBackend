<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{
    public function index()
    {
        return view('admin.DiamondMaster.Orders.index');
    }

    public function fetch()
    {
        $orders = Order::with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();  

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        return view('admin.DiamondMaster.Orders.invoice', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        $pdf = Pdf::loadView('admin.DiamondMaster.Orders.invoice', compact('order'));
        return $pdf->download("Invoice-{$order->order_id}.pdf");
    }
    public function sendInvoice(Request $request, Order $order)
    {
        try {
            $to = $request->query('to', 'user');

            if ($to === 'admin') {
                $email = config('mail.from.address');
            } else {
                $email = $order->user->email 
                        ?? optional(json_decode($order->address))->email
                        ?? null;
            }

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid email found for sending invoice.'
                ], 400);
            }

            $pdf = Pdf::loadView('admin.DiamondMaster.Orders.invoice', compact('order'));
            $pdfPath = 'invoices/' . $order->order_id . '.pdf';
            Storage::disk('s3')->put($pdfPath, $pdf->output());
            $pdfUrl = Storage::disk('s3')->url($pdfPath);

            $viewPath = 'admin.DiamondMaster.emails.email_template_invoice';
            
            if (!view()->exists($viewPath)) {
                throw new \Exception("View [$viewPath] not found");
            }

            $htmlContent = view($viewPath, [
                'order' => $order,
                'downloadUrl' => $pdfUrl
            ])->render();

            Mail::send([], [], function ($message) use ($order, $email, $htmlContent) {
                $message->to($email)
                        ->subject("Invoice - {$order->order_id}")
                        ->html($htmlContent);
            });

            return response()->json([
                'success' => true,
                'message' => "Invoice sent to " . ($to === 'admin' ? 'admin' : 'customer') . " successfully."
            ]);
            
        } catch (\Exception $e) {
            Log::error('Invoice sending error: '.$e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function changeStatus(Request $request, Order $order)
    {
        // Define valid status transitions
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => ['cancelled'], // Allow cancellation after delivery
            'cancelled' => [], // No transitions allowed
        ];

        $currentStatus = $order->order_status;
        $newStatus = $request->order_status;

        // Check if status change is allowed
        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            return response()->json([
                'success' => false,
                'message' => "Cannot change status from " . ucfirst($currentStatus) . " to " . ucfirst($newStatus)
            ], 400);
        }

        // Update the status
        $order->update(['order_status' => $newStatus]);

        return response()->json([
            'success'    => true,
            'message'    => 'Status updated successfully',
            'new_status' => $newStatus
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'user_name'      => 'required|string',
            'contact_number' => 'required|string',
            'items_id'       => 'required|array',
            'item_details'   => 'required|array',
            'total_price'    => 'required|numeric',
            'shipping_cost'  => 'nullable|numeric',
            'discount'       => 'nullable|numeric',
            'coupon_code'    => 'nullable|string',
            'address'        => 'required|array',
            'is_gift'        => 'boolean',
            'payment_mode'   => 'required|string',
            'payment_id'     => 'nullable|string',
            'payment_status' => 'required|string',
            'order_status'   => 'required|string',
            'delivery_date'  => 'nullable|date',
            'notes'          => 'nullable|string',
            'referrer'       => 'nullable|string',
        ]);

        $data['order_id'] = 'ORD-' . Str::uuid();
        Order::create($data);

        return redirect()->route('orders.index')
                         ->with('success', 'Order created successfully');
    }
}