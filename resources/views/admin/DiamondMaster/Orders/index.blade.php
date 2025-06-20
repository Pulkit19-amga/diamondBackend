@extends('admin.layouts.master')

@section('main_section')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Success/Error Message Container -->
  <div id="messageContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;">
    <div class="alert alert-dismissible fade show" role="alert">
      <span id="messageText"></span>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
  
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
      <h4>Orders Management</h4>
    </div>
    <div class="card-body table-responsive text-nowrap">
      <table class="table table-hover" id="orderTable">
        <thead class="bg-light">
          <tr>
            <th>#</th>
            <th>Order ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="orderTableBody"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invoice Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="invoiceContent"></div>
    </div>
  </div>
</div>

<script>
$(function(){
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  });

  let orderTableInitialized = false;

  // Show message function
  function showMessage(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    $('#messageContainer').show();
    $('#messageContainer .alert')
      .removeClass('alert-success alert-danger')
      .addClass(alertClass)
      .find('#messageText')
      .text(message);
    
    setTimeout(() => {
      $('#messageContainer').fadeOut();
    }, 3000);
  }

  function loadOrders(){
    $.get("{{ route('orders.fetch') }}", function(orders){
      let rows = '';
      orders.forEach((o, index) => {
        const badgeClass = {
          pending:   'secondary',
          confirmed: 'primary',
          shipped:   'info',
          delivered: 'success',
          cancelled: 'danger'
        }[o.order_status] || 'secondary';

        const serialNo = index + 1;
        rows += `
          <tr id="order-row-${o.id}">
            <td>${serialNo}</td>
            <td>${o.order_id}</td>
            <td>${o.user_name}</td>
            <td>${o.user && o.user.email ? o.user.email : '—'}</td>
            <td>₹${parseFloat(o.total_price).toFixed(2)}</td>
            <td>
              <span class="badge bg-${badgeClass}">
                ${o.order_status.charAt(0).toUpperCase() + o.order_status.slice(1)}
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-primary preview-invoice" data-id="${o.id}">
                Preview
              </button>
            </td>
          </tr>`;
      });

      if (orderTableInitialized) {
        $('#orderTable').DataTable().clear().destroy();
      }

      $('#orderTableBody').html(rows);
      $('#orderTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10,
        language: {
          paginate: {
            previous: '←',
            next: '→'
          }
        }
      });
      orderTableInitialized = true;
    });
  }

  loadOrders();

  $(document).on('click', '.preview-invoice', function () {
    const id = $(this).data('id');
    $.get(`{{ url('admin/orders') }}/${id}`, function(html){
      $('#invoiceContent').html(html);
      $('#invoiceModal').modal('show');
    });
  });
});
</script>
@endsection