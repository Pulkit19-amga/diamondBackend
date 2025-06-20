<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 30px; background-color: #ffffff; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Order Invoice</p>
        </div>
        
        <div class="content">
            <p>Hello {{ $order->user_name }},</p>
            
            <p>Your order #{{ $order->order_id }} has the invoice attached.</p>
            
            <p><strong>Order Details:</strong></p>
            <ul>
                <li>Order ID: {{ $order->order_id }}</li>
                <li>Date: {{ $order->created_at->format('d/m/Y') }}</li>
                <li>Total Amount: ₹{{ number_format($order->total_price + $order->shipping_cost - $order->discount, 2) }}</li>
                <li>Status: {{ ucfirst($order->order_status) }}</li>
            </ul>
            
            <p>The attached PDF invoice contains the complete details.</p>
            
            <p>Thank you,<br>The Team</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>
