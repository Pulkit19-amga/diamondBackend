@extends('admin.layouts.master')

@section('main_section')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="mb-3">Diamond Master</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#diamondModal" id="addDiamondBtn">
                Add New Diamond
            </button>
        </div>

        <div class="table-responsive text-nowrap card-body">
            <table class="table table-hover" id="diamondTable">
                <thead>
                    <tr class="bg-light">
                        <th>ID</th>
                        <th>Vendor</th>
                        <th>Shape</th>
                        <th>Color</th>
                        <th>Clarity</th>
                        <th>Carat</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diamonds as $diamond)
                    <tr>
                        <td>{{ $diamond->diamondid }}</td>
                        <td>{{ $diamond->vendor->vendor_company_name ?? '-' }}</td>
                        <td>{{ $diamond->shape }}</td>
                        <td>{{ $diamond->color }}</td>
                        <td>{{ $diamond->clarity }}</td>
                        <td>{{ $diamond->carat_weight }}</td>
                        <td>${{ number_format($diamond->price, 2) }}</td>
                        <td>
                            @if($diamond->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $diamond->date_added ? \Carbon\Carbon::parse($diamond->date_added)->format('Y-m-d') : '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-info editDiamond" data-id="{{ $diamond->diamondid }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger deleteDiamond" data-id="{{ $diamond->diamondid }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                
                </tbody>
                
            </table>
        </div>
    </div>
</div>

<!-- Diamond Modal -->
<div class="modal fade" id="diamondModal" tabindex="-1" aria-labelledby="diamondModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form id="diamondForm">
            @csrf
            <input type="hidden" id="diamond_id" name="diamondid">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Diamond Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    @php
                    $diamondFields = [
                        ['diamond_type', 'Diamond Type', 'select', [1 => 'Natural Diamond', 2 => 'Lab Diamond']],
                        ['quantity', 'Quantity', 'number'],
                        ['vendor_id', 'Vendor', 'select', $vendors],
                        ['shape', 'Shape', 'select', $shapes],
                        ['color', 'Color', 'select', $colors],
                        ['clarity', 'Clarity', 'select', $clarities],
                        ['cut', 'Cut', 'select', $cuts],
                        ['carat_weight', 'Carat Weight', 'number'],
                        ['price_per_carat', 'Price/Carat', 'number'],
                        ['vendor_price', 'Vendor Price', 'number'],
                        ['certificate_number', 'Certificate Number'],
                        ['certificate_date', 'Certificate Date', 'date'],
                        ['polish', 'Polish', 'select', $polishes],
                        ['symmetry', 'Symmetry', 'select', $symmetries],
                        ['fluorescence', 'Fluorescence', 'select', $fluorescences],
                        ['availability', 'Availability', 'select', [
                            0 => 'Hold', 
                            1 => 'Available',
                            2 => 'Memo'
                        ]],
                        ['is_superdeal', 'Super Deal', 'select', [
                            1 => 'Yes', 
                            0 => 'No'
                        ]],
                        ['status', 'Status', 'select', [
                            1 => 'Active', 
                            0 => 'Inactive'
                        ]],
                        ['date_added', 'Date Added', 'datetime-local'],
                        ['date_updated', 'Date Updated', 'datetime-local']
                    ];
                    @endphp

                    @foreach ($diamondFields as $field)
                        @php
                            $name = $field[0];
                            $label = $field[1];
                            $type = $field[2] ?? 'text';
                            $options = $field[3] ?? [];
                        @endphp

                        <div class="col-md-4 mb-3">
                            <label for="{{ $name }}" class="form-label">{{ $label }}</label>

                            @if($type === 'select')
                                <select class="form-select" id="{{ $name }}" name="{{ $name }}">
                                    <option value="">-- Select --</option>
                                    @if(!empty($options))
                                        @foreach($options as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @else
                                <input type="{{ $type }}" 
                                       class="form-control" 
                                       id="{{ $name }}" 
                                       name="{{ $name }}">
                            @endif

                            @error($name)
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endforeach

                    <div id="diamondFormError" class="col-12 text-danger mt-2"></div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary me-2" id="saveDiamondBtn">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function formatDateForInput(dateString) {
    if (!dateString) return "";
    const dt = new Date(dateString);
    const pad = n => n.toString().padStart(2, '0');
    return `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
}

$(document).ready(function() {
   
    $('#addDiamondBtn').click(function() {
        $('#diamondForm')[0].reset();
        $('#diamond_id').val('');
        $('#diamondFormError').text('');
        $('#saveDiamondBtn').text('Save');
    });

    // Edit Diamond
    $('#diamondTable').on('click', '.editDiamond', function() {
        const id = $(this).data('id');
        $.get(`/admin/diamond-master/${id}/edit`, function(data) {
            $('#diamond_id').val(data.diamondid);
            
            // Populate form fields
            @foreach($diamondFields as $field)
                @php $name = $field[0]; @endphp
                @if(in_array($field[2] ?? 'text', ['datetime-local', 'date']))
                    $('#{{ $name }}').val(formatDateForInput(data.{{ $name }}));
                @else
                    $('#{{ $name }}').val(data.{{ $name }});
                @endif
            @endforeach

            $('#diamondModal').modal('show');
            $('#saveDiamondBtn').text('Update');
        });
    });

    // Save/Update Diamond
    $('#diamondForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = $('#diamond_id').val();
        const url = id ? `/admin/diamond-master/${id}` : "{{ route('diamond-master.store') }}";
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#diamondModal').modal('hide');
                diamondTable.ajax.reload();
                toastr.success('Diamond saved successfully!');
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let errorMsg = '';
                for(const [key, value] of Object.entries(errors)) {
                    errorMsg += `${value.join('<br>')}`;
                }
                $('#diamondFormError').html(errorMsg || 'An error occurred');
            }
        });
    });

    // Delete Diamond
    $('#diamondTable').on('click', '.deleteDiamond', function() {
        const id = $(this).data('id');
        if(confirm('Are you sure to delete this diamond?')) {
            $.ajax({
                url: `/admin/diamond-master/${id}`,
                type: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function() {
                    diamondTable.ajax.reload();
                    toastr.success('Diamond deleted successfully!');
                }
            });
        }
    });

    // Status Toggle
    $('#diamondTable').on('change', '.statusToggle', function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked') ? 1 : 0;
        
        $.ajax({
            url: `/admin/diamond-master/${id}/status`,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function() {
                toastr.success('Status updated!');
            }
        });
    });
});
</script>
@endsection