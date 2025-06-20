@extends('admin.layouts.master')

@section('main_section')
<style>
    table.dataTable td.dt-control:before {
        background: #317cb1;
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="mb-0">Product Style Categories</h4>
            <button class="btn btn-primary" id="addPSCBtn">Add New</button>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table table-hover" id="pscTable" style="width: 100%;">
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
<div class="modal fade" id="pscModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="pscForm" class="modal-content">
            @csrf
            <input type="hidden" id="psc_id" name="psc_id">
            <div class="modal-header">
                <h5 class="modal-title">Product Style Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="psc_name" id="psc_name" class="form-control">
                    <small class="text-danger error-psc_name"></small>
                </div>

                <!-- Alias -->
                <div class="mb-3">
                    <label class="form-label">Alias <span class="text-danger">*</span></label>
                    <input type="text" name="psc_alias" id="psc_alias" class="form-control">
                    <small class="text-danger error-psc_alias"></small>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="text" name="psc_image" id="psc_image" class="form-control">
                    <small class="text-danger error-psc_image"></small>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="psc_status" id="psc_status" class="form-select">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <small class="text-danger error-psc_status"></small>
                </div>

                <!-- Display In Front -->
                <div class="mb-3">
                    <label class="form-label">Display In Front</label>
                    <select name="psc_display_in_front" id="psc_display_in_front" class="form-select">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <small class="text-danger error-psc_display_in_front"></small>
                </div>

                <!-- Sort Order -->
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="psc_sort_order" id="psc_sort_order" class="form-control">
                    <small class="text-danger error-psc_sort_order"></small>
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

const pscTable = $('#pscTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("product-style-category.index") }}',
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
                width: "20px"
            },
            { data: 'psc_id', name: 'psc_id' },
            { data: 'psc_name', name: 'psc_name' },
            { data: 'psc_alias', name: 'psc_alias' },
            { 
                data: 'psc_status',
                render: function(data) {
                    return data == 1 ? 'Active' : 'Inactive';
                }
            },
            { 
                data: 'psc_display_in_front',
                render: function(data) {
                    return data == 1 ? 'Yes' : 'No';
                }
            },
            { data: 'psc_sort_order', name: 'psc_sort_order' }
        ]
    });

    // Toggle expand row for Style Categories
    $('#pscTable tbody').on('click', 'td.dt-control', function () {
        const tr = $(this).closest('tr');
        const row = pscTable.row(tr);
        const data = row.data();

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(`
                <div class="d-flex gap-2 p-2">
                    <button class="btn btn-sm btn-info editPSCBtn" data-id="${data.psc_id}">
                        <i class="fa fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger deletePSCBtn" data-id="${data.psc_id}">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            `).show();
            tr.addClass('shown');
        }
    });

    const pscModal = new bootstrap.Modal(document.getElementById('pscModal'));

    $('#addPSCBtn').click(function() {
        $('#pscForm')[0].reset();
        $('#psc_id').val('');
        $('.text-danger').text('');
        pscModal.show();
    });

    $('#pscForm').on('submit', function(e) {
        e.preventDefault();
        $('.text-danger').text('');

        let id = $('#psc_id').val();
        let url = id ? '{{ url("admin/product-style-category/update") }}/' + id : '{{ route("product-style-category.store") }}';

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                toastr.success(response.message);
                pscTable.ajax.reload();
                pscModal.hide();
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

    $(document).on('click', '.editPSCBtn', function() {
        let id = $(this).data('id');
        $.get('{{ url("admin/product-style-category/edit") }}/' + id, function(data) {
            $('#psc_id').val(data.psc_id);
            $('#psc_name').val(data.psc_name);
            $('#psc_alias').val(data.psc_alias);
            $('#psc_image').val(data.psc_image);
            $('#psc_status').val(data.psc_status);
            $('#psc_display_in_front').val(data.psc_display_in_front);
            $('#psc_sort_order').val(data.psc_sort_order);
            $('.text-danger').text('');
            pscModal.show();
        });
    });

    $(document).on('click', '.deletePSCBtn', function() {
        if (!confirm("Are you sure you want to delete this category?")) return;
        let id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/product-style-category/delete") }}/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
                pscTable.ajax.reload();
            },
            error: function() {
                toastr.error('Something went wrong.');
            }
        });
    });
});
</script>
@endsection
