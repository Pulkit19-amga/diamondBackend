    <form id="diamondForm" class="needs-validation" novalidate>
      @csrf
      <input type="hidden" name="id" id="diamond_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5>Diamond Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          @php
            $fields = [
              ['diamond_type','Type','select',[1=>'Natural',2=>'Lab']],
              ['quantity','Qty','number'],
              ['vendor_id','Vendor','select',$vendors],
              ['shape','Shape','select',$shapes],
              ['color','Color','select',$colors],
              ['clarity','Clarity','select',$clarities],
              ['carat_weight','Carat','number'],
              ['price_per_carat','Price/Carat','number'],
              ['vendor_price','Vendor Price','number'],
              ['certificate_company','Cert. Co.','select',$certificate_companies],
              ['certificate_number','Cert. No.','text'],
              ['certificate_date','Cert. Date','date'],
              ['availability','Avail.','select',[0=>'Hold',1=>'Avail',2=>'Memo']],
              ['is_superdeal','Super Deal','select',[1=>'Yes',0=>'No']],
              ['status','Status','select',[1=>'Active',0=>'Inactive']],
              ['date_added','Date Added','datetime-local'],
              ['date_updated','Date Updated','datetime-local'],
            ];
          @endphp

          @foreach($fields as $f)
            @php list($name,$label,$type,$opts)=array_pad($f,4,[]) @endphp
            <div class="col-md-4">
              <label for="{{ $name }}" class="form-label">{{ $label }}</label>
              @if($type==='select')
                <select name="{{ $name }}" id="{{ $name }}"
                        class="form-select" required>
                  <option value="">-- Select --</option>
                  @foreach($opts as $k=>$v)
                    <option value="{{ $k }}">{{ $v }}</option>
                  @endforeach
                </select>
              @else
                <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
                       class="form-control" required>
              @endif
              <div class="invalid-feedback" id="error_{{ $name }}"></div>
            </div>
          @endforeach

          <div class="col-12 text-danger" id="formError"></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary"
                  data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>