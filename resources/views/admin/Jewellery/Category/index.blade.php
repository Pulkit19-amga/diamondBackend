@extends('admin.layouts.master')

@section('main_section')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="mb-3">Category</h4>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#categoryModal"
                    id="addcategoryBtn">
                    Add New
                </button>
            </div>

            <div class="table-responsive text-nowrap card-body">
                <table class="table table-hover" id="categoryTable">
                    <thead>
                        <tr class="bg-light">
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Alias</th>
                            <th>Status</th>
                            <th>Display Front</th>
                            <th>Sort Order</th>
                            <th>Date Added</th>
                            <th>Date Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Records will be loaded by JS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="categoryForm">
                @csrf
                <input type="hidden" id="record_id" name="id">
                <input type="hidden" name="update_mode" value="full">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Category Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        @php
                            $fields = [
                                ['category_name', 'Category Name'],
                                ['category_alias', 'Alias'],
                                ['category_description', 'Description', 'textarea'],
                                ['is_display_front', 'Display on Front', 'select', ['1' => 'Yes', '0' => 'No']],
                                ['category_image', 'Image'],
                                ['category_header_banner', 'Header Banner'],
                                ['category_status', 'Status', 'select', ['1' => 'Active', '0' => 'Inactive']],
                                ['seo_url', 'SEO URL'],
                                ['category_meta_title', 'Meta Title'],
                                ['category_meta_description', 'Meta Description'],
                                ['category_meta_keyword', 'Meta Keywords'],
                                ['category_h1_tag', 'H1 Tag'],
                                ['sort_order', 'Sort Order', 'number'],
                                ['deleted', 'Deleted', 'select', ['0' => 'No', '1' => 'Yes']],
                            ];
                        @endphp

                        @foreach ($fields as $field)
                            @php
                                $name = $field[0];
                                $label = $field[1];
                                $type = $field[2] ?? 'text';
                                $options = $field[3] ?? [];
                            @endphp

                            <div class="col-md-4">
                                <label for="{{ $name }}" class="form-label">{{ $label }}</label>

                                @if ($type === 'select')
                                    <select class="form-select" id="{{ $name }}" name="{{ $name }}">
                                        <option value="">-- Select --</option>
                                        @foreach ($options as $val => $option)
                                            <option value="{{ $val }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="{{ $type }}" class="form-control" id="{{ $name }}"
                                        name="{{ $name }}" value="{{ old($name) }}">
                                @endif

                                @error($name)
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endforeach

                        <div id="formError" class="col-12 text-danger mt-2"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary me-2" id="savecategoryBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatDateForInput(dasteString) {
            if (!dateString) return "";
            let dt = new Date(dateString);
            let year = dt.getFullYear();
            let month = ("0" + (dt.getMonth() + 1)).slice(-2);
            let day = ("0" + dt.getDate()).slice(-2);
            let hours = ("0" + dt.getHours()).slice(-2);
            let minutes = ("0" + dt.getMinutes()).slice(-2);
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        $(document).ready(function() {
            fetchRecords();

            function fetchRecords() {
                $.get("{{ route('category.index') }}", function(response) {
                    let rows = '';
                    response.forEach(r => {
                        rows += `
                            <tr>
                                    <td>${r.id}</td>
        <td>${r.category_name ?? ''}</td>
        <td>${r.category_alias ?? ''}</td>
        <td><input type="checkbox" ${r.category_status == 1 ? 'checked' : ''} class="status" data-id="${r.id}"></td>
        <td><input type="checkbox" ${r.is_display_front == 1 ? 'checked' : ''} class="status" data-id="${r.id}"></td>
        <td><input type="number" value="${r.sort_order}" class="sort-order" data-id="${r.id}" style="width: 60px;"></td>
        <td>${r.category_date_added ? r.category_date_added.substring(0, 10) : ''}</td>
        <td>${r.category_date_modified ? r.category_date_modified.substring(0, 10) : ''}</td>
        <td>
            <button class="btn btn-sm btn-info editBtn" data-id="${r.id}"><i class="fa fa-edit"></i></button>
            <button class="btn btn-sm btn-danger deleteBtn" data-id="${r.id}"><i class="fa fa-trash"></i></button>
        </td>

                            </tr>
                        `;
                    });
                    renderDataTable('categoryTable', rows);
                });
            }

            $('#addcategoryBtn').on('click', function() {
                $('#categoryForm')[0].reset();
                $('#record_id').val('');
                $('#formError').text('');
                $('#categoryForm .text-danger').remove();
                $('#savecategoryBtn').text('Save');
            });

            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                console.log(id);
                $.get("{{ url('/admin/category') }}/" + id, function(data) {
                    $('#categoryForm')[0].reset();
                    $('#formError').text('');
                    $('#record_id').val(data.id);
                    @foreach ($fields as $field)
                        @php
                            $name = $field[0];
                            $type = $field[2] ?? 'text';
                        @endphp

                       @if ($type === 'select')
    {
        let val_{{ $name }} = data['{{ $name }}'];
        if (val_{{ $name }} === true) val_{{ $name }} = '1';
        else if (val_{{ $name }} === false) val_{{ $name }} = '0';
        else if (val_{{ $name }} === null || val_{{ $name }} === undefined) val_{{ $name }} = '';
        $('#{{ $name }}').val(val_{{ $name }});
    }
@else
    $('#{{ $name }}').val(data['{{ $name }}'] ?? '');
@endif

                    @endforeach


                    $('#categoryModal').modal('show');
                    $('#savecategoryBtn').text('Update');
                });
            });

            $(document).ready(function() {
                let deleteId = null;
                let $currentRow = null;

                $(document).on('click', '.deleteBtn', function() {

                    deleteId = $(this).data('id');
                    $currentRow = $(this).closest('tr');
                    $('.popup-modal.remove-modal').fadeIn(); // Show the modal
                });

                // Close modal on No or overlay click
                $(document).on('click', '.close-pop', function() {
                    $('.popup-modal.remove-modal').fadeOut(); // Hide the modal
                });

                // Confirm delete
                $('#confirmDelete').on('click', function() {
                    if (!deleteId) return;

                    $.ajax({
                        url: `/api/admin/category/${deleteId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $currentRow.remove();
                                toastr.success(response.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr.error("Unexpected server response.");
                            }
                            $('.popup-modal.remove-modal').fadeOut(); // Close the modal
                        },
                        error: function(xhr) {
                            toastr.error("Failed to delete the record.");
                            $('.popup-modal.remove-modal').fadeOut(); // Close the modal
                        }
                    });
                });
            });
            $('#categoryForm').submit(function(e) {
                e.preventDefault();
                const id = $('#record_id').val();

                const method = id ? 'PUT' : 'POST';
                const url = id ?
                    `{{ url('admin/category') }}/${id}` :
                    "{{ route('category.store') }}";
                let formData = $(this).serialize();
                formData += `&_method=${method}`;
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function() {
                        $('#categoryModal').modal('hide');
                        fetchRecords();
                        toastr.success("Record saved successfully!");
                    },
                    error: function(xhr) {
                        $('#formError').html('');
                        $('#categoryForm .text-danger').remove();

                        let errors = xhr.responseJSON?.errors || {};

                        $.each(errors, function(key, messages) {
                            const input = $(`#categoryForm [name="${key}"]`);
                            if (input.length) {
                                input.after(
                                    `<small class="text-danger">${messages[0]}</small>`
                                );
                            }
                        });

                    }
                });
            });
            $(document).on('change', '.status', function() {
                const id = $(this).data('id');
                const status = $(this).prop('checked') ? 1 : 0;

                $.ajax({
                    url: `{{ url('admin/category') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        category_status: status
                    },
                    success: function() {
                        toastr.success('Status updated successfully!');
                    },
                    error: function() {
                        toastr.error('Failed to update Status!');
                    }
                });
            });


            $(document).on('blur', '.sort-order', function() {
                const id = $(this).data('id');
                const sortOrder = $(this).val();

                $.ajax({
                    url: `{{ url('admin/category') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        sort_order: sortOrder
                    },
                    success: function() {
                        toastr.success('Sort order updated successfully!');
                    },
                    error: function() {
                        toastr.error('Failed to update sort order!');
                    }
                });
            });


        });
    </script>
@endsection
