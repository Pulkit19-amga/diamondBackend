@extends('admin.layouts.master')

@section('main_section')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h4 class="mb-3">Diamond Quality Group Management</h4>
      <button class="btn btn-primary btn-sm" id="createNewDQG">Add New</button>
    </div>
    <div class="card-body table-responsive text-nowrap">
      <table class="table table-hover" id="dqgTable">
        <thead class="bg-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Alias</th>
            <th>Short Name</th>
            <th>Sort Order</th>
            <th>Status</th>
            <th>Origin</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

{{-- Bootstrap Modal for Add/Edit --}}
<div class="modal fade" id="dqgModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="dqgForm" class="modal-content">
      @csrf
      {{-- Hidden field for primary key --}}
      <input type="hidden" id="dqg_id" name="dqg_id">

      <div class="modal-header">
        <h5 class="modal-title">Diamond Quality Group Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        {{-- Name --}}
        <div class="mb-3">
          <label for="dqg_name" class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" id="dqg_name" name="dqg_name" class="form-control">
          <div id="error_dqg_name" class="text-danger"></div>
        </div>

        {{-- Alias --}}
        <div class="mb-3">
          <label for="dqg_alias" class="form-label">Alias</label>
          <input type="text" id="dqg_alias" name="dqg_alias" class="form-control">
          <div id="error_dqg_alias" class="text-danger"></div>
        </div>

        {{-- Short Name --}}
        <div class="mb-3">
          <label for="dqg_short_name" class="form-label">Short Name</label>
          <input type="text" id="dqg_short_name" name="dqg_short_name" class="form-control">
          <div id="error_dqg_short_name" class="text-danger"></div>
        </div>

        {{-- Description --}}
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" name="description" class="form-control"></textarea>
          <div id="error_description" class="text-danger"></div>
        </div>

        {{-- Icon URL --}}
        <div class="mb-3">
          <label for="dqg_icon" class="form-label">Icon URL</label>
          <input type="text" id="dqg_icon" name="dqg_icon" class="form-control">
          <div id="error_dqg_icon" class="text-danger"></div>
        </div>

        {{-- Sort Order --}}
        <div class="mb-3">
          <label for="dqg_sort_order" class="form-label">Sort Order</label>
          <input type="number" id="dqg_sort_order" name="dqg_sort_order" class="form-control">
          <div id="error_dqg_sort_order" class="text-danger"></div>
        </div>

        {{-- Status --}}
        <div class="mb-3">
          <label for="dqg_status" class="form-label">Status</label>
          <select id="dqg_status" name="dqg_status" class="form-control">
            <option value="">Select Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
          <div id="error_dqg_status" class="text-danger"></div>
        </div>

        {{-- Origin --}}
        <div class="mb-3">
          <label for="dqg_origin" class="form-label">Origin</label>
          <input type="text" id="dqg_origin" name="dqg_origin" class="form-control">
          <div id="error_dqg_origin" class="text-danger"></div>
        </div>

        {{-- Generic form error --}}
        <div id="formError" class="text-danger mt-2"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Page-specific JavaScript --}}
<script>
(function($){
  $(function(){
    // Initialize Bootstrap Modal instance
    const modal = new bootstrap.Modal(document.getElementById('dqgModal'));

    // Clear validation errors and messages
    function clearValidation(){
      $('#dqgForm .form-control').removeClass('is-invalid');
      $('[id^="error_"]').text('');
      $('#formError').text('');
    }

    // Fetch existing records and render DataTable
    function fetchRecords(){
      $.get("{{ route('diamondqualitygroup.fetch') }}", data => {
        let rows = data.table_data; 
        // Since controller returns HTML for table rows, inject directly:
        $('#dqgTable tbody').html(rows);

        // Initialize or reinitialize DataTable
        if ( $.fn.DataTable.isDataTable('#dqgTable') ) {
          $('#dqgTable').DataTable().destroy();
        }
        $('#dqgTable').DataTable({
          order: [[0, 'desc']],
          pageLength: 10,
          language: {
            paginate: {
              previous: '←',
              next: '→'
            }
          }
        });
      }).fail(() => {
        $('#dqgTable tbody').html('<tr><td colspan="10" class="text-center">Error fetching data</td></tr>');
      });
    }

    // Initial fetch
    fetchRecords();

    // Show "Add New" Modal
    $('#createNewDQG').click(() => {
      clearValidation();
      $('#dqgForm')[0].reset();
      $('#dqg_id').val('');
      $('#saveBtn').text('Save');
      modal.show();
    });

    // Show "Edit" Modal with data populated
    $(document).on('click', '.edit', function(){
      clearValidation();
      const id = $(this).data('id');
      $.get(`{{ url('admin/diamondqualitygroup/edit') }}/${id}`, data => {
        const d = data.data;
        $('#dqg_id').val(d.dqg_id);
        $('#dqg_name').val(d.dqg_name);
        $('#dqg_alias').val(d.dqg_alias);
        $('#dqg_short_name').val(d.dqg_short_name);
        $('#description').val(d.description);
        $('#dqg_icon').val(d.dqg_icon);
        $('#dqg_sort_order').val(d.dqg_sort_order);
        $('#dqg_status').val(d.dqg_status);
        $('#dqg_origin').val(d.dqg_origin);
        $('#saveBtn').text('Update');
        modal.show();
      }).fail(() => {
        $('#formError').text('Failed to fetch data.');
      });
    });

    // Handle form submit for Add/Update
    $('#dqgForm').submit(function(e){
      e.preventDefault();
      clearValidation();

      const id = $('#dqg_id').val();
      // Determine URL and HTTP method based on existence of id
      const url = id 
        ? `{{ url('admin/diamondqualitygroup/update') }}/${id}` 
        : `{{ route('diamondqualitygroup.store') }}`;
      const methodOverride = id ? '_method=POST' : '';

      $.ajax({
        url: url,
        type: 'POST',
        data: $(this).serialize() + (id ? `&${methodOverride}` : ''),
        success: res => {
          // Assuming toastr is loaded in master layout
          toastr.success(res.success);
          modal.hide();
          fetchRecords();
        },
        error: xhr => {
          if(xhr.status === 422){
            // Validation errors
            const errors = xhr.responseJSON.errors;
            Object.keys(errors).forEach(field => {
              $(`[name="${field}"]`).addClass('is-invalid');
              $(`#error_${field}`).text(errors[field][0]);
            });
          } else {
            $('#formError').text('Something went wrong.');
            toastr.error('Error saving data');
          }
        }
      });
    });

    // Handle Delete
    $(document).on('click', '.delete', function(){
      if(!confirm('Are you sure you want to delete this record?')) return;
      const id = $(this).data('id');
      $.ajax({
        url: `{{ url('admin/diamondqualitygroup/destroy') }}/${id}`,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: res => {
          toastr.success(res.success);
          fetchRecords();
        },
        error: () => {
          toastr.error('Failed to delete');
        }
      });
    });

  });
})(jQuery);
</script>
@endsection
