@extends('admin.layouts.master')
@section('main_section')
<div class="container py-4">
  <h4>Add New Diamond</h4>
  <form action="{{ route('diamond-master.store') }}" method="POST">
    @csrf
    <div class="row g-3">
      @php
        $fields = [
          ['diamond_type','Type','select',[1=>'Natural',2=>'Lab']],
          ['quantity','Quantity','number'],
          ['vendor_id','Vendor','select',$vendors],
          ['shape','Shape','select',$shapes],
          ['color','Color','select',$colors],
          ['clarity','Clarity','select',$clarities],
          ['carat_weight','Carat Weight','number'],
          ['price_per_carat','Price per Carat','number'],
          ['vendor_price','Vendor Price','number'],
          ['certificate_company','Cert. Company','select',$labs],
          ['certificate_number','Cert. Number','text'],
          ['certificate_date','Cert. Date','date'],
          ['availability','Availability','select',[0=>'Hold',1=>'Available',2=>'Memo']],
          ['status','Status','select',[1=>'Active',0=>'Inactive']],
        ];
      @endphp

      @foreach($fields as $f)
        @php list($name,$label,$type,$opts)=array_pad($f,4,[]) @endphp
        <div class="col-md-4">
          <label for="{{ $name }}" class="form-label">{{ $label }}</label>
          @if($type==='select')
            <select name="{{ $name }}" id="{{ $name }}" class="form-select @error($name) is-invalid @enderror">
              <option value="">-- Select --</option>
              @foreach($opts as $k=>$v)
                <option value="{{ $k }}" {{ old($name)=="$k"?'selected':'' }}>{{ $v }}</option>
              @endforeach
            </select>
          @else
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" value="{{ old($name) }}">
          @endif
          @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      @endforeach
    </div>
    <div class="mt-3">
      <button type="submit" class="btn btn-success">Save</button>
      <a href="{{ route('diamond-master.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection