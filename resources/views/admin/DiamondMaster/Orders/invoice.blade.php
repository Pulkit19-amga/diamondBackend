<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $order->order_id }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
          body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .invoice-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            max-width: 1000px;
            margin: 0 auto;
            padding: 1.25rem;
        }
        .invoice-left {
            flex: 0 0 65%;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.25rem;
            background-color: #fff;
        }
        .invoice-right {
            flex: 0 0 30%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .card-header-plain {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            vertical-align: middle;
        }
        .invoice-table th {
            background-color: #f1f1f1;
            font-weight: 600;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .modal-body {
            background-color: #fefefe;
        }
        .btn-100 {
            width: 100%;
        }
        .status-disabled {
            opacity: 0.6;
            pointer-events: none;
        }
        #actionMessage {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-option {
            display: none;
        }
    </style>
</head>
<body>
  <div class="invoice-container">
    <div class="invoice-left">
            <div class="mb-4 text-center">
        <h2 class="fw-bold">INVOICE</h2>
        <p class="mb-1"><strong>Order ID:</strong> {{ $order->order_id }}</p>
        <p class="text-muted mb-0"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
      </div>

      <div class="mb-4">
        <h5 class="fw-semibold">Customer Information</h5>
        <p class="mb-1"><strong>Name:</strong> {{ $order->user_name }}</p>
        <p class="mb-1"><strong>Email:</strong> {{ $order->user ? $order->user->email : '—' }}</p>
        <p class="mb-1"><strong>Contact:</strong> {{ $order->contact_number }}</p>
        <p class="mb-0"><strong>Address:</strong>
          {{ optional(json_decode($order->address))->street ?? 'N/A' }}, 
          {{ optional(json_decode($order->address))->city ?? '' }}, 
          {{ optional(json_decode($order->address))->state ?? '' }}
        </p>
      </div>

      <table class="table invoice-table mb-3">
        <thead>
          <tr>
            <th>Item</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Price</th>
            <th class="text-end">Total</th>
          </tr>
        </thead>
        <tbody>
          @php $items = json_decode($order->item_details, true) ?? []; @endphp
          @forelse($items as $item)
            <tr>
              <td>{{ $item['name'] ?? 'Unknown Product' }}</td>
              <td class="text-center">{{ $item['quantity'] ?? 0 }}</td>
              <td class="text-end">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
              <td class="text-end">₹{{ number_format( ($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">No items found in this order</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="mb-4">
        <div class="summary-row">
          <span>Subtotal:</span>
          <span>₹{{ number_format($order->total_price, 2) }}</span>
        </div>
        <div class="summary-row">
          <span>Shipping:</span>
          <span>₹{{ number_format($order->shipping_cost, 2) }}</span>
        </div>
        <div class="summary-row">
          <span>Discount:</span>
          <span class="text-danger">-₹{{ number_format($order->discount, 2) }}</span>
        </div>
        <div class="summary-row fw-semibold border-top pt-2">
          <span>Grand Total:</span>
          <span>₹{{ number_format($order->total_price + $order->shipping_cost - $order->discount, 2) }}</span>
        </div>
      </div>
    </div>

    <div class="invoice-right">
      <!-- Status Change Card -->
        <div class="card">
        <div class="card-header card-header-plain">
          Change Order Status
        </div>
        <div class="card-body">
          <select id="statusSelect" class="form-select mb-2" {{ $order->order_status === 'cancelled' ? 'disabled' : '' }}>
            <!-- Status options will be populated dynamically -->
            <option value="" disabled selected>Select status</option>
            <option class="status-option" value="confirmed" data-visible-for="pending">Confirmed</option>
            <option class="status-option" value="shipped" data-visible-for="confirmed">Shipped</option>
            <option class="status-option" value="delivered" data-visible-for="shipped">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
          
          <div class="mb-2">
            <strong>Current Status:</strong>
            <span class="badge bg-{{ 
                $order->order_status === 'pending' ? 'secondary' : 
                ($order->order_status === 'confirmed' ? 'primary' : 
                ($order->order_status === 'shipped' ? 'info' : 
                ($order->order_status === 'delivered' ? 'success' : 'danger')))
            }}">
              {{ ucfirst($order->order_status) }}
            </span>
          </div>
          
          <button id="updateStatusBtn" class="btn btn-primary btn-100" {{ $order->order_status === 'cancelled' ? 'disabled' : '' }}>
            Update Status
          </button>
          
          <div id="statusMessage" class="mt-2" style="display: none;"></div>
        </div>
      </div>

      <!-- Invoice Actions Card -->
      <div class="card">
        <div class="card-header card-header-plain">
          Invoice Actions
        </div>
        <div class="card-body">
          <select id="actionSelect" class="form-select mb-2">
            <option selected disabled>Select Action</option>
            <option value="download">Download Invoice</option>
            <option value="send_user">Send to Customer</option>
            <option value="send_admin">Send to Admin</option>
          </select>
          <button id="performActionBtn" class="btn btn-success btn-100">
            Go
          </button>
          
          <!-- Action message -->
          <div id="actionMessage" class="mt-2" style="display: none;"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-4 mb-3 text-muted">
    <small>Thank you for your business!</small>
  </div>

<script>
    $(function(){
      const orderId = {{ $order->id }};
      const currentStatus = "{{ $order->order_status }}";
      
      const downloadUrl  = '{{ url("admin/orders/$order->id/invoice/download") }}';
      const sendUrlBase  = '{{ url("admin/orders/$order->id/invoice/send") }}';
      const statusUrl    = '{{ url("admin/orders/$order->id/status") }}';
      const indexUrl     = '{{ route("orders.index") }}';

      // Show only valid next statuses
      function setupStatusDropdown() {
        // Hide all status options
        $('.status-option').hide();
        
        // Show only the next valid status options
        $('.status-option[data-visible-for="' + currentStatus + '"]').show();
        
        // Always show cancelled option
        $('#statusSelect option[value="cancelled"]').show();
        
        // Set default selection to first visible option
        $('#statusSelect option:visible').first().prop('selected', true);
      }
      
      // Initialize status dropdown
      setupStatusDropdown();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'Accept': 'application/json'
        }
      });

      // Show message function
      function showMessage(elementId, message, type) {
        const element = $('#' + elementId);
        element.removeClass('alert-success alert-danger')
               .addClass('alert-' + type)
               .text(message)
               .show();
        
        setTimeout(() => {
          element.fadeOut();
        }, 3000);
      }

      // Update Status
      $('#updateStatusBtn').on('click', function(){
        const newStatus = $('#statusSelect').val();
        const btn = $(this);
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Updating...');
        
        $.ajax({
          url: statusUrl,
          method: 'PATCH',
          data: { order_status: newStatus },
          success: function(res) {
            showMessage('statusMessage', 'Status updated successfully!', 'success');
            
            // Disable the form after successful update
            $('#statusSelect').prop('disabled', true);
            btn.prop('disabled', true).text('Update Status');
            
            // Redirect after 2 seconds
            setTimeout(() => {
              window.location.href = indexUrl;
            }, 2000);
          },
          error: function(xhr) {
            btn.prop('disabled', false).text('Update Status');
            const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? 
                            xhr.responseJSON.message : 
                            'Error updating status. Please try again.';
            showMessage('statusMessage', errorMsg, 'danger');
          }
        });
      });

      // Perform Invoice Action
      $('#performActionBtn').on('click', function(){
        const action = $('#actionSelect').val();
        const btn = $(this);
        
        if (!action) {
          showMessage('actionMessage', 'Please select an action first.', 'danger');
          return;
        }
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Processing...');
        
        if (action === 'download') {
          window.open(downloadUrl, '_blank');
          showMessage('actionMessage', 'Invoice download started!', 'success');
          btn.prop('disabled', false).text('Go');
        }
        else if (action === 'send_user' || action === 'send_admin') {
          const to = action === 'send_user' ? 'user' : 'admin';
          const url = sendUrlBase + '?to=' + to;
          
          $.ajax({
            url: url,
            method: 'GET',
            success: function(res) {
              const recipient = to === 'user' ? 'customer' : 'admin';
              showMessage('actionMessage', 'Invoice sent to ' + recipient + ' successfully!', 'success');
              btn.prop('disabled', false).text('Go');
            },
            error: function(xhr) {
              const recipient = to === 'user' ? 'customer' : 'admin';
              const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? 
                              xhr.responseJSON.message : 
                              'Failed to send invoice to ' + recipient + '.';
              showMessage('actionMessage', errorMsg, 'danger');
              btn.prop('disabled', false).text('Go');
            }
          });
        }
        else {
          showMessage('actionMessage', 'Invalid action selected.', 'danger');
          btn.prop('disabled', false).text('Go');
        }
      });
    });
</script>
</body>
</html>
   