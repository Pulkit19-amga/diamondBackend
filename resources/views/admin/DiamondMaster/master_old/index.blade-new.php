@extends('admin.layouts.master')
@section('main_section')
<div class="container-xxl py-4">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
      <h4>Diamond Master</h4>
      <button class="btn btn-primary btn-sm" id="addDiamondBtn">Add New</button>
    </div>
    <div class="card-body">
      <table class="table" id="diamondTable">
        <thead>
          <tr>
            <th>ID</th><th>Vendor</th><th>Shape</th><th>Color</th>
            <th>Clarity</th><th>Carat</th><th>Price</th><th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="diamondModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
 @include('admin.layouts.diamondform')
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function($){
  const modalEl = document.getElementById('diamondModal');
  const diamondModal = new bootstrap.Modal(modalEl);

  function clearValidation(){
    $('#diamondForm').removeClass('was-validated');
    $('#diamondForm').find('.is-invalid').removeClass('is-invalid');
    $('#diamondForm').find('[id^=error_]').text('');
    $('#formError').text('');
  }

  function renderDataTable(id, rowsHtml){
    if($.fn.DataTable.isDataTable('#'+id)){
      $('#'+id).DataTable().clear().destroy();
    }
    $('#'+id+' tbody').html(rowsHtml);
    $('#'+id).DataTable({ paging:true, searching:true, ordering:true });
  }

  function fetch(){
    $.get("{{ route('diamond-master.data') }}", data=>{
      console.log("Fetched Data:", data);
      const html = data.map(r=>`
        <tr>
          <td>${r.diamondid}</td>
          <td>${r.vendor.vendor_name}</td>
          <td>${r.shape.name}</td>
        
          <td>${r.clarity.name}</td>
          <td>${r.carat_weight}</td>
          <td>${r.price_per_carat}</td>
          <td>
            <div class="d-flex align-items-center">
              <input type="checkbox"
                     class="form-check-input me-2 toggle-status"
                     data-id="${r.diamondid}"
                     ${r.status?'checked':''}>
              <button class="btn btn-info btn-sm me-1 editBtn"
                      data-id="${r.diamondid}">
                <i class="fa fa-edit"></i>
              </button>
              <button class="btn btn-danger btn-sm deleteBtn"
                      data-id="${r.diamondid}">
                <i class="fa fa-trash"></i>
              </button>
            </div>
          </td>
        </tr>`).join('');
      renderDataTable('diamondTable', html);
    });
  }

  $(function(){
    fetch();

    // Add New
    $('#addDiamondBtn').click(()=>{
      clearValidation();
      $('#diamondForm')[0].reset();
      diamondModal.show();
    });

    // Edit
    $(document).on('click','.editBtn',function(){
      clearValidation();
      const id = $(this).data('id');
      $.get(`/admin/diamond-master/${id}`, d=>{
        $('#diamond_id').val(d.diamondid);
        Object.entries(d).forEach(([k,v])=>{
          $(`[name="${k}"]`).val(v||'');
        });
        diamondModal.show();
      });
    });

    $('#diamondForm').on('submit', function(e){
  e.preventDefault();
  const form = this;
  if (!form.checkValidity()) {
    form.classList.add('was-validated');
    return;
  }
  clearValidation();
  const id = $('#diamond_id').val();
  const url = id ? `/admin/diamond-master/${id}` : "{{ route('diamond-master.store') }}";
  const method = id ? 'PUT' : 'POST';
  $.ajax({
    url,
    type:'POST',
    data: $(form).serialize() + `&_method=${method}`,
    dataType: 'json',
    success() {
  toastr.success('Your Data Successfully Saved');
  diamondModal.hide();
  fetch();
},
    error(xhr){
      if (xhr.status === 422) {
        $.each(xhr.responseJSON.errors, (field, msgs) => {
          $(`[name="${field}"]`).addClass('is-invalid');
          $(`#error_${field}`).text(msgs[0]);
        });
      } else {
        $('#formError').text('Unexpected error occurred');
      }
    }
  });
});
    // Toggle status
    $(document).on('change','.toggle-status',function(){
      const id = $(this).data('id'),
            status = this.checked ? 1 : 0;
      $.post(`/admin/diamond-master/update-status/${id}`, {
        _token:'{{ csrf_token() }}',
        status
      });
    });

    // Delete
    $(document).on('click','.deleteBtn',function(){
      if (!confirm('Are you sure?')) return;
      const id = $(this).data('id');
      $.ajax({
        url:`/admin/diamond-master/${id}`,
        type:'DELETE',
        data:{ _token:'{{ csrf_token() }}' },
        success(){
          toastr.success('Deleted');
          fetch();
        }
      });
    });

  });
})(jQuery);
</script>
@endsection
