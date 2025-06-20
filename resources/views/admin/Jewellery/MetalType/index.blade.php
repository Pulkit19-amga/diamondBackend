@extends('admin.layouts.master')

@section('main_section')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h4 class="mb-3">Metal Type Management</h4>
      <button class="btn btn-primary" id="addMetalBtn">Add New</button>
    </div>
    <div class="card-body table-responsive text-nowrap">
      <table class="table table-hover" id="metalTable">
        <thead class="bg-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Tooltip</th>
            <th>Status</th>
            <th>Sort Order</th>
            <th>Color</th>
            <th>Icon</th> 
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal for Add/Edit -->
<div class="modal fade" id="metalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="metalForm" class="modal-content">
      @csrf
      <input type="hidden" id="dmt_id" name="dmt_id">
      <div class="modal-header">
        <h5 class="modal-title">Metal Type Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        {{-- Name --}}
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" id="dmt_name" name="dmt_name" class="form-control">
          <small class="text-danger error-dmt_name"></small>
        </div>

        {{-- Tooltip --}}
        <div class="mb-3">
          <label class="form-label">Tooltip</label>
          <input type="text" id="dmt_tooltip" name="dmt_tooltip" class="form-control">
          <small class="text-danger error-dmt_tooltip"></small>
        </div>

        {{-- Status --}}
        <div class="mb-3">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select id="dmt_status" name="dmt_status" class="form-select">
            <option value="">Select Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
          <small class="text-danger error-dmt_status"></small>
        </div>

        {{-- Sort Order --}}
        <div class="mb-3">
          <label class="form-label">Sort Order</label>
          <input type="number" id="sort_order" name="sort_order" class="form-control" style="width: 100px;">
          <small class="text-danger error-sort_order"></small>
        </div>

        {{-- Color Code --}}
        <div class="mb-3">
          <label class="form-label">Color Code</label>
          <input type="text" id="color_code" name="color_code" class="form-control" placeholder="#FFFFFF">
          <small class="text-danger error-color_code"></small>
        </div>

        {{-- Icon --}}
        <div class="mb-3">
          <label class="form-label">Icon</label>
          <input type="text" id="metal_icon" name="metal_icon" class="form-control" placeholder="fa-icon-class">
          <small class="text-danger error-metal_icon"></small>
        </div>

        <div id="formError" class="text-danger mt-2"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="saveMetalBtn">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Page-specific JavaScript --}}
<script>
$(function(){
  const modalEl   = document.getElementById('metalModal'),
        metalModal = new bootstrap.Modal(modalEl);

  function clearValidation(){
    $('#metalForm .form-control').removeClass('is-invalid');
    $('[class^="error-"]').text('');
    $('#formError').text('');
  }

  function fetchRecords(){
    $.get("{{ route('metaltype.fetch') }}", res => {
      let rows = res.map(m => `
        <tr>
          <td>${m.dmt_id}</td>
          <td>${m.dmt_name || ''}</td>
          <td>${m.dmt_tooltip || ''}</td>
          <td>
            <input type="checkbox" class="toggle-status" data-id="${m.dmt_id}" ${m.dmt_status == 1 ? 'checked' : ''}>
          </td>
          <td>
            <input type="number" class="sort-order form-control" data-id="${m.dmt_id}" value="${m.sort_order || ''}" style="width:80px">
          </td>
          <td style="background:${m.color_code || '#fff'}; color: #000;">
            ${m.color_code || ''}
          </td>
          <td>${m.metal_icon || ''}</td>
          <td>
            <button class="btn btn-sm btn-info editBtn" data-id="${m.dmt_id}">
              <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger deleteBtn" data-id="${m.dmt_id}">
              <i class="fa fa-trash"></i>
            </button>
          </td>
        </tr>
      `).join('');
      $('#metalTable tbody').html(rows);

      if ($.fn.DataTable.isDataTable('#metalTable')) {
        $('#metalTable').DataTable().destroy();
      }
      $('#metalTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10,
        language: {
          paginate: {
            previous: '←',
            next: '→'
          }
        }
      });
    });
  }

  fetchRecords();

  $('#addMetalBtn').click(() => {
    clearValidation();
    $('#metalForm')[0].reset();
    $('#dmt_id').val('');
    $('#saveMetalBtn').text('Save');
    $('#successMessage').html('');
    metalModal.show();
  });

  $('#metalForm').submit(function(e){
    e.preventDefault();
    clearValidation();

    const id     = $('#dmt_id').val();
    const url    = id 
      ? `{{ url('admin/metal-type/update') }}/${id}` 
      : `{{ route('metaltype.store') }}`;
    const method = id ? 'PUT' : 'POST';

    $.ajax({
      url: url,
      type: 'POST',
      data: $(this).serialize() + (id ? '&_method=PUT' : ''),
      success: res => {
        toastr.success(res.success);
        metalModal.hide();
        setTimeout(() => location.reload(), 1000); // page reload
      },
      error: xhr => {
        if (xhr.status === 422) {
          const errors = xhr.responseJSON.errors;
          Object.keys(errors).forEach(field => {
            $(`#${field}`).addClass('is-invalid');
            $(`.error-${field}`).text(errors[field][0]);
          });
        } else {
          $('#formError').text('Something went wrong.');
          toastr.error('Error saving data');
        }
      }
    });
  });

  $(document).on('click', '.editBtn', function(){
    clearValidation();
    const id = $(this).data('id');

    $.get(`{{ url('admin/metal-type/show') }}/${id}`, data => {
      $('#dmt_id').val(data.dmt_id);
      $('#dmt_name').val(data.dmt_name);
      $('#dmt_tooltip').val(data.dmt_tooltip);
      $('#dmt_status').val(data.dmt_status);
      $('#sort_order').val(data.sort_order);
      $('#color_code').val(data.color_code);
      $('#metal_icon').val(data.metal_icon);
      $('#saveMetalBtn').text('Update');
      $('#successMessage').html('');
      metalModal.show();
    });
  });

  $(document).on('click', '.deleteBtn', function(){
    if (!confirm('Are you sure you want to delete this record?')) return;
    const id = $(this).data('id');

    $.ajax({
      url: `{{ url('admin/metal-type/delete') }}/${id}`,
      type: 'DELETE',
      data: { _token: '{{ csrf_token() }}' },
      success: res => {
        toastr.success(res.success);
        setTimeout(() => location.reload(), 1000); // page reload
      },
      error: () => toastr.error('Failed to delete')
    });
  });

  $(document).on('change', '.toggle-status', function(){
    const id     = $(this).data('id');
    const status = this.checked ? 1 : 0;

    $.post(`{{ url('admin/metal-type/update') }}/${id}`, {
      _token: '{{ csrf_token() }}',
      _method: 'PUT',
      dmt_status: status
    }).done(() => {
      toastr.success('Status updated');
      setTimeout(() => location.reload(), 1000); // page reload
    }).fail(() => toastr.error('Status update failed'));
  });

  $(document).on('blur', '.sort-order', function(){
    const id   = $(this).data('id');
    const sort = $(this).val();

    $.post(`{{ url('admin/metal-type/update') }}/${id}`, {
      _token: '{{ csrf_token() }}',
      _method: 'PUT',
      sort_order: sort
    }).done(() => {
      toastr.success('Sort order updated');
      setTimeout(() => location.reload(), 1000);
    }).fail(() => toastr.error('Sort order update failed'));
  });

});
</script>
@endsection
