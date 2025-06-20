@extends('admin.layouts.master')

@section('main_section')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Product Color Master</h4>
      <button class="btn btn-primary btn-sm" id="addColorBtn">Add New</button>
    </div>
    <div class="card-body table-responsive text-nowrap">
        <table class="table table-hover" id="colorTable">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Short Name</th>
              <th>Alias</th>
              <th>Remark</th>
              <th>Front</th>
              <th>Sort</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="colorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="colorForm" class="modal-content">
      @csrf
      <input type="hidden" id="color_id" name="color_id">
      <div class="modal-header">
        <h5 class="modal-title">Color Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Color Name</label>
          <input type="text" name="name" id="name" class="form-control">
          <small class="text-danger error-name"></small>
        </div>
        <div class="mb-3">
          <label class="form-label">Short Name</label>
          <input type="text" name="short_name" id="short_name" class="form-control">
          <small class="text-danger error-short_name"></small>
        </div>
        <div class="mb-3">
          <label class="form-label">Alias</label>
          <input type="text" name="alias" id="alias" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Remark</label>
          <input type="text" name="remark" id="remark" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Display in Front</label>
            <select name="display_in_front" id="display_in_front" class="form-select">
                <option value="">Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <small class="text-danger error-display_in_front"></small>
        </div>

        <div class="mb-3">
          <label class="form-label">Sort Order</label>
          <input type="number" name="sort_order" id="sort_order" class="form-control">
          <small class="text-danger error-sort_order"></small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function () {
    const colorModal = new bootstrap.Modal(document.getElementById('colorModal'));

    let table = $('#colorTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("product-color.fetch") }}',
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'short_name' },
            { data: 'alias' },
            { data: 'remark' },
            {
              data: 'display_in_front',
              render: function(data) {
                return data == 1 ? 'Yes' : 'No';
              }
            },
            { data: 'sort_order' },
            { data: 'action', orderable: false, searchable: false },
        ]
    });

    $('#addColorBtn').click(function() {
        $('#colorForm')[0].reset();
        $('.text-danger').text('');
        $('#color_id').val('');
        $('#saveBtn').text('Save');
        $('#successMessage').html('');
        colorModal.show();
    });

    $('#colorForm').on('submit', function (e) {
        e.preventDefault();
        $('.text-danger').text('');
        let id = $('#color_id').val();
        let url = id 
            ? 'product-color-master/update/' + id 
            : '{{ route("product-color.store") }}';

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                $('#colorForm')[0].reset();
                $('#color_id').val('');
                $('#saveBtn').text('Save');
                table.ajax.reload();
                colorModal.hide();

                 // **Show Toastr Success** (same as Metal Type)
                toastr.success(res.success);
            },
            error: function(xhr) {
                $.each(xhr.responseJSON.errors, function(key, val) {
                    $('.error-' + key).text(val[0]);
                });
            }
        });
    });

    $(document).on('click', '.editBtn', function () {
        let id = $(this).data('id');
        $.get('product-color-master/edit/' + id, function (data) {
            $('#color_id').val(data.id);
            $('#name').val(data.name);
            $('#short_name').val(data.short_name);
            $('#alias').val(data.alias);
            $('#remark').val(data.remark);
            $('#display_in_front').val(data.display_in_front);
            $('#sort_order').val(data.sort_order);
            $('#saveBtn').text('Update');
            $('#successMessage').html('');
            colorModal.show();
        });
    });

    $(document).on('click', '.deleteBtn', function () {
        if (confirm('Are you sure to delete?')) {
            let id = $(this).data('id');
            $.ajax({
                url: 'product-color-master/delete/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    table.ajax.reload();
                    let successHtml = `
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${res.success}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>`;
                    $('#successMessage').html(successHtml);
                }
            });
        }
    });
});
</script>
@endsection
