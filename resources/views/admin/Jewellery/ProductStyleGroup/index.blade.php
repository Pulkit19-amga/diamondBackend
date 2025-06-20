@extends('admin.layouts.master')

@section('main_section')
<style>
    table.dataTable td.dt-control:before {
        background: #317cb1;
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Product Style Groups</h4>
            <button class="btn btn-primary" id="addPSGBtn">Add New</button>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table table-hover" id="psgTable">
                <thead class="bg-light">
                    <tr>
                        <th>Action</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Alias</th>
                        <th>Status</th>
                        <th>Display In Front</th>
                        <th>Sort Order</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="psgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="psgForm" class="modal-content">
            @csrf
            <input type="hidden" id="psg_id" name="psg_id">
            <div class="modal-header">
                <h5 class="modal-title">Product Style Group Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="psg_name" id="psg_name" class="form-control">
                    <small class="text-danger error-psg_name"></small>
                </div>

                <!-- Alias -->
                <div class="mb-3">
                    <label class="form-label">Alias <span class="text-danger">*</span></label>
                    <input type="text" name="psg_alias" id="psg_alias" class="form-control">
                    <small class="text-danger error-psg_alias"></small>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="text" name="psg_image" id="psg_image" class="form-control">
                    <small class="text-danger error-psg_image"></small>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="psg_status" id="psg_status" class="form-select">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <small class="text-danger error-psg_status"></small>
                </div>

                <!-- Display In Front -->
                <div class="mb-3">
                    <label class="form-label">Display In Front</label>
                    <select name="psg_display_in_front" id="psg_display_in_front" class="form-select">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <small class="text-danger error-psg_display_in_front"></small>
                </div>

                <!-- Sort Order -->
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="psg_sort_order" id="psg_sort_order" class="form-control">
                    <small class="text-danger error-psg_sort_order"></small>
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
          $(document).ready(function() {
    // Initialize Toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000
    };
  const psgTable = $('#psgTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("product-style-group.index") }}',
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
                width: "20px"
            },
            { data: 'psg_id' },
            { data: 'psg_name' },
            { data: 'psg_alias' },
            { 
                data: 'psg_status',
                render: function(data) {
                    return data == 1 ? 'Active' : 'Inactive';
                }
            },
            { 
                data: 'psg_display_in_front',
                render: function(data) {
                    return data == 1 ? 'Yes' : 'No';
                }
            },
            { data: 'psg_sort_order' }
        ]
    });

    // Toggle expand row for Style Groups
    $('#psgTable tbody').on('click', 'td.dt-control', function () {
        const tr = $(this).closest('tr');
        const row = psgTable.row(tr);
        const data = row.data();

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(`
                <div class="d-flex gap-2 p-2">
                    <button class="btn btn-sm btn-info editPSGBtn" data-id="${data.psg_id}">
                        <i class="fa fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger deletePSGBtn" data-id="${data.psg_id}">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            `).show();
            tr.addClass('shown');
        }
    });

    const psgModal = new bootstrap.Modal(document.getElementById('psgModal'));

    $('#addPSGBtn').click(function() {
        $('#psgForm')[0].reset();
        $('#psg_id').val('');
        $('.text-danger').text('');
        psgModal.show();
    });

    $('#psgForm').on('submit', function(e) {
        e.preventDefault();
        $('.text-danger').text('');

        let id = $('#psg_id').val();
        let url = id 
            ? '{{ url("admin/product-style-group/update") }}/' + id 
            : '{{ route("product-style-group.store") }}';

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                toastr.success(response.message);
                psgTable.ajax.reload();
                psgModal.hide();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.error-' + key).text(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong.');
                }
            }
        });
    });

    $(document).on('click', '.editPSGBtn', function() {
        let id = $(this).data('id');
        $.get('{{ url("admin/product-style-group/edit") }}/' + id, function(data) {
            $('#psg_id').val(data.psg_id);
            $('#psg_name').val(data.psg_name);
            $('#psg_alias').val(data.psg_alias);
            $('#psg_image').val(data.psg_image);
            $('#psg_status').val(data.psg_status);
            $('#psg_display_in_front').val(data.psg_display_in_front);
            $('#psg_sort_order').val(data.psg_sort_order);
            $('.text-danger').text('');
            psgModal.show();
        });
    });

    $(document).on('click', '.deletePSGBtn', function() {
        if (!confirm("Are you sure you want to delete this group?")) return;
        let id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/product-style-group/delete") }}/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
                psgTable.ajax.reload();
            },
            error: function() {
                toastr.error('Something went wrong.');
            }
        });
    });

});
</script>
@endsection
