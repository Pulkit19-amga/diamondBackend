@extends('admin.layouts.master')

@section('main_section')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <h4>Contact Us Submissions</h4>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-striped">
        <thead class="bg-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Question</th>
            <th>Date Submitted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($contacts as $c)
            <tr>
              <td>{{ $c->id }}</td>
              <td>{{ $c->name }}</td>
              <td>{{ $c->email }}</td>
              <td>{{ $c->phone ?? '—' }}</td>
              <td>{{ $c->topic }}</td>
              <td>{{ $c->question }}</td>
              <td>{{ $c->created_at?->format('d-m-Y') ?? '—' }}</td>
              <td>
                <button class="btn btn-sm btn-warning mailBtn" 
                        data-id="{{ $c->id }}" 
                        data-email="{{ $c->email }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#mailModal">
                  <i class="fa fa-envelope"></i> <b style="padding-left: 3px;">Send</b>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center">No records found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Send Mail Modal -->
<div class="modal fade" id="mailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="mailForm">
      @csrf
      <input type="hidden" name="id" id="mailUserId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Send Email</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">To:</label>
            <input type="email" id="mailUserEmail" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" id="mailSubject">
            <div class="invalid-feedback" id="error_subject"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="body" class="form-control" rows="4" id="mailBody"></textarea>
            <div class="invalid-feedback" id="error_body"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(function(){
  // clear previous errors
  function clearErrors() {
    $('#error_subject, #error_body').text('');
    $('#mailSubject, #mailBody').removeClass('is-invalid');
  }

  // when show modal, populate fields
  $('#mailModal').on('show.bs.modal', function(event) {
    clearErrors();
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var email = button.data('email');
    $('#mailUserId').val(id);
    $('#mailUserEmail').val(email);
    $('#mailSubject, #mailBody').val('');
  });

  // submit mail form
  $('#mailForm').submit(function(e){
    e.preventDefault();
    clearErrors();
    var id = $('#mailUserId').val();
    var url = "{{ route('contact.sendMail', ['id' => ':id']) }}".replace(':id', id);
    var data = $(this).serialize();

    $.post(url, data)
      .done(function(res){
        $('#mailModal').modal('hide');
        alert(res.message);
      })
      .fail(function(xhr){
        if(xhr.status === 422) {
          var errors = xhr.responseJSON.errors;
          if(errors.subject) {
            $('#mailSubject').addClass('is-invalid');
            $('#error_subject').text(errors.subject[0]);
          }
          if(errors.body) {
            $('#mailBody').addClass('is-invalid');
            $('#error_body').text(errors.body[0]);
          }
        } else {
          alert('Error sending email.');
        }
      });
  });
});
</script>
@endsection
