@extends('admin.layouts.master')

@section('main_section')
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Diamond Master</h4>
                <a href="{{ route('diamond-master.create') }}" class="btn btn-primary">
                    Add New Diamond
                </a>
            </div>
            <div class="card-body">
                <table id="diamondTable" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vendor</th>
                            <th>Shape</th>
                            <th>Color</th>
                            <th>Clarity</th>
                            <th>Carat</th>
                            <th>Price/Carat</th>
                            <th>date added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#diamondTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('diamond-master.data') }}',
                    type: 'GET'
                },
                 responsive: {
            details: {
                type: 'inline' 
            }
        },
        scrollX: false,
                columns: [{
                        data: 'diamondid'
                    },
                    {
                        data: 'vendor.vendor_name',
                        defaultContent: '—'
                    },
                    {
                        data: 'shape.name',
                        defaultContent: '—'
                    },
                    {
                        data: 'color.name',
                        defaultContent: '—'
                    },
                    {
                        data: 'clarity.name',
                        defaultContent: '—'
                    },
                    {
                        data: 'carat_weight'
                    },
                    {
                        data: 'price_per_carat'
                    },
                    {
                        data: 'date_added'
                    },
                    {
                        
                        data: 'diamondid',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                    <a href="/admin/diamond-master/${data}/edit" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${data}"><i class="fa fa-trash"></i></button>
                `;
                        }
                    }
                ]
            });


            // Delete handler
            $(document).on('click', '.deleteBtn', function() {
                if (!confirm('Are you sure you want to delete?')) return;

                let id = $(this).data('id');

                $.post(`/admin/diamond-master/${id}`, {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                }).done(() => {
                     $('#diamondTable').DataTable().ajax.reload(); // Reload data after deletion
                });
            });
        });
    </script>
@endsection
