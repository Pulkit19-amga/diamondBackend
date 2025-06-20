@extends('admin.layouts.master')

@section('main_section')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h4 class="mb-3">Products Clarity Master Management</h4>
      <button class="btn btn-primary btn-sm" id="addClarityBtn">Add New</button>
    </div>
    <div class="card-body table-responsive text-nowrap">
      <table class="table table-hover" id="clarityTable">
        <thead class="bg-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Alias</th>
            <th>Remark</th>
            <th>Display In Front</th>
            <th>Sort Order</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal for Add/Edit -->
<div class="modal fade" id="clarityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="clarityForm" class="modal-content">
      @csrf
      <input type="hidden" id="pcm_id" name="id">

      <div class="modal-header">
        <h5 class="modal-title">Clarity Master Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- Name -->
        <div class="mb-3">
          <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" id="name" name="name" class="form-control">
          <span class="text-danger" id="name_error"></span>
        </div>

        <!-- Alias -->
        <div class="mb-3">
          <label for="alias" class="form-label">Alias</label>
          <input type="text" id="alias" name="alias" class="form-control">
          <span class="text-danger" id="alias_error"></span>
        </div>

        <!-- Remark -->
        <div class="mb-3">
          <label for="remark" class="form-label">Remark</label>
          <input type="text" id="remark" name="remark" class="form-control">
          <span class="text-danger" id="remark_error"></span>
        </div>

        <!-- Display In Front -->
        <div class="mb-3">
          <label for="display_in_front" class="form-label">Display In Front</label>
          <select id="display_in_front" name="display_in_front" class="form-select">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
          <span class="text-danger" id="display_in_front_error"></span>
        </div>

        <!-- Sort Order -->
        <div class="mb-3">
          <label for="sort_order" class="form-label">Sort Order</label>
          <input type="number" id="sort_order" name="sort_order" class="form-control">
          <span class="text-danger" id="sort_order_error"></span>
        </div>

        <div id="formError" class="text-danger mt-2"></div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="saveClarityBtn">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
(function($){
  $(function(){
    // Initialize Bootstrap Modal instance
    const modalEl     = document.getElementById('clarityModal'),
          clarityModal = new bootstrap.Modal(modalEl);

    // Clear validation classes and error messages
    function clearValidation() {
      $('#clarityForm').find('.is-invalid').removeClass('is-invalid');
      $('[id$="_error"]').text('');
      $('#formError').text('');
    }

    // Fetch and render table rows via AJAX
    function fetchRecords() {
      $.get("{{ route('ProductClarity.fetch') }}", function(res) {
        let rows = '';
        if (res.table_data.trim() !== '') {
          rows = res.table_data;
        } else {
          rows = '<tr><td colspan="7" class="text-center">No Data Found</td></tr>';
        }
        $('#clarityTable tbody').html(rows);
      }).fail(function(xhr) {
        console.error(xhr.responseText);
        $('#clarityTable tbody').html('<tr><td colspan="7" class="text-center">Error fetching data</td></tr>');
      });
    }

    // Initial data fetch
    fetchRecords();

    // "Add New" button click → open empty modal
    $('#addClarityBtn').click(function(){
      clearValidation();
      $('#clarityForm')[0].reset();
      $('#pcm_id').val('');
      $('#saveClarityBtn').text('Save');
      clarityModal.show();
    });

    // Handle form submit for Create/Update
    $('#clarityForm').submit(function(e){
      e.preventDefault();
      clearValidation();

      const id     = $('#pcm_id').val();
      const url    = id
        ? "{{ url('admin/product-clarity-master/update') }}/" + id
        : "{{ route('ProductClarity.store') }}";
      const method = 'POST'; // store and update both use POST with optional _method

      $.ajax({
        url: url,
        type: method,
        data: $(this).serialize() + (id ? '&_method=PUT' : ''),
        dataType: 'json',
        success: function(response) {
          toastr.success(response.success || 'Saved successfully!');
          clarityModal.hide();
          fetchRecords();
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            $.each(errors, function(field, messages) {
              $('#' + field).addClass('is-invalid');
              $('#' + field + '_error').text(messages[0]);
            });
          } else {
            $('#formError').text('Something went wrong.');
            toastr.error('Error saving data');
            console.error(xhr.responseText);
          }
        }
      });
    });

    // Edit record: fetch single row and populate modal
    $(document).on('click', '.editBtn', function(){
      clearValidation();
      const id = $(this).data('id');
      $.get(`{{ url('admin/product-clarity-master/edit') }}/${id}`, function(response) {
        const data = response.data;
        $('#pcm_id').val(data.id);
        $('#name').val(data.name);
        $('#alias').val(data.alias);
        $('#remark').val(data.remark);
        $('#display_in_front').val(data.display_in_front);
        $('#sort_order').val(data.sort_order);
        $('#saveClarityBtn').text('Update');
        clarityModal.show();
      }).fail(function(xhr) {
        toastr.error('Failed to fetch data.');
        console.error(xhr.responseText);
      });
    });

    // Delete record via AJAX
    $(document).on('click', '.deleteBtn', function(){
      if (!confirm('Are you sure you want to delete this record?')) return;
      const id = $(this).data('id');
      $.ajax({
        url: `{{ url('admin/product-clarity-master/destroy') }}/${id}`,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
          toastr.success(response.success || 'Deleted successfully!');
          fetchRecords();
        },
        error: function(xhr) {
          toastr.error('Failed to delete record.');
          console.error(xhr.responseText);
        }
      });
    });

  });
})(jQuery);
</script>
@endsection
