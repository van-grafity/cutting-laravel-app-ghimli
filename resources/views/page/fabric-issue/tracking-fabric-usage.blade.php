@extends('layouts.master')

@section('title', 'Tracking Fabric Usage')

<!-- <select class="selectpicker" multiple data-live-search="true">
  <option>Mustard</option>
  <option>Ketchup</option>
  <option>Relish</option>
</select> -->
@section('content')
<!-- multiselect dropdown -->
<div>
    Tracking Fabric Usage
</div>
@endsection

@push('js')
<script type="text/javascript">
    $document.ready(function(){
        $('.selectpicker').selectpicker();
    });
</script>
@endpush
    