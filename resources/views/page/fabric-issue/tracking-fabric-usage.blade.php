@extends('layouts.master')

@section('title', 'Tracking Fabric Usage')

@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ff8000;
        border: 1px solid #ff8000;
        color: #fff;
        padding: 0 10px;
        height: 1.5rem;
        margin-top: .31rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,.7);
        float: right;
        margin-left: 5px;
        margin-right: -2px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        border-color: #006fe6;
        min-height: 38px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #006fe6;
        box-shadow: none;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>GL Number</label>
                        <select id="gl_no" name="gl_no[]" class="form-control select2" multiple="multiple" data-placeholder="Choose GL" placeholder="Choose GL" style="width: 100%;" required>
                            @foreach ($gls as $gl)
                                <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Print</a>
                        <a href="javascript:void(0);" class="btn btn-primary mb-2 mr-2" id="btn_detail_report" hidden>Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "{{ route('fabric-issue.tracking-fabric-usage-report') }}";
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#gl_no').select2();
        
        $('#btn_print_report').click(function(){
            let gl_ids = [];
            $('#gl_no option:selected').each(function(){
                gl_ids.push($(this).val());
            });
            if(gl_ids.length > 0){
                let gl_ids_str = gl_ids.join(',');
                window.open(url + '?gl_ids=' + gl_ids_str, '_blank');
            }else{
                alert('Please select GL Number');
            }
        });
    });
</script>
@endpush
    