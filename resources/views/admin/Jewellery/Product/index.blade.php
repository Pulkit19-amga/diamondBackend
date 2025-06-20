@extends('admin.layouts.master')

@section('main_section')
<style>
    table.dataTable td.dt-control:before {
        background: #337ab7;
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h4 class="mb-0">Product Management</h4>
      <a href="{{ route('product.create') }}" class="btn btn-primary">Add New Product</a>
    </div>
    <div class="card-body">
      <table id="productTable" class="table table-hover table-striped">
        <thead class="bg-light">
          <tr>
            <th></th> 
            <th>#</th> 
            <th>Name</th> 
            <th>SKU</th>   
            <th>Price</th>   
            <th>Images</th>  
            <th>Status</th>     
            <th>Added Date</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<script>
     const productEditUrl = "{{ url('/admin/product') }}";
function format(rowData) {
   const deleteUrl = "{{ route('product.destroy', ['id' => ':id']) }}".replace(':id', rowData.products_id);
  return `
    <div class="d-flex">
      <a href="${productEditUrl}/${rowData.products_id}/edit" class="btn btn-sm btn-primary me-2">Edit</a>
        <button data-url="${deleteUrl}" class="btn btn-sm btn-danger deleteBtn">Delete</button>
    </div>
  `;
}

$(document).ready(function() {
  var table = $('#productTable').DataTable({
    processing: true,
    serverSide: false, // Changed to client-side processing
    ajax: '{{ route("product.index") }}',
    columns: [
      {
        className: 'dt-control',
        orderable: false,
        searchable: false,
        data: null,
        defaultContent: '',
      },
      { 
        data: 'DT_RowIndex', 
        name: 'DT_RowIndex', 
        orderable: false, 
        searchable: false 
      },
{
  data: 'products_name',
  name: 'products_name',
  render: function(data) {
    return data.length > 10 ? data.substring(0, 10) + '...' : data;
  }
},
      { data: 'products_sku', name: 'products_sku' },
      { data: 'products_price', name: 'products_price' },
      { 
  data: 'featured_image', 
  name: 'featured_image', 
  orderable: false, 
  searchable: false 
},
      { 
        data: 'products_status', 
        name: 'products_status' 
      },
      { data: 'date_added', name: 'date_added' },
    ],
    order: [[1, 'desc']],
    columnDefs: [
      {
        targets: 5, // Images column
        render: function(data) {
          return data; // Output raw HTML
        }
      },
      {
        targets: 6, // Status column
        render: function(data) {
          return data; // Output raw HTML
        }
      },
      {
        targets: 4, // Price Column
        render: function(data, type, row) {
          // Already formatted on server, just return
          return data;
        }
      },
      {
        targets: 7, // Date Column
        render: function(data) {
          return data ? new Date(data).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
          }) : '';
        }
      }
    ]
  });

  // Add event listener for opening and closing details
  $('#productTable tbody').on('click', 'td.dt-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('shown');
    } else {
      row.child(format(row.data())).show();
      tr.addClass('shown');
    }
  });

  // Delete button click handler
$('#productTable tbody').on('click', '.deleteBtn', function() {
    const url = $(this).data('url'); 

    if(confirm('Are you sure you want to delete this product?')) {
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                toastr.success(response.message);
                $('#productTable').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'There was a problem deleting the product.');
            }
        });
    }
});
});
</script>

@endsection
