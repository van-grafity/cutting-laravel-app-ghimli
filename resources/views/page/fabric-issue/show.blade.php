@extends('layouts.master')

@section('title', 'Fabric Issue')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="detail-section my-5 px-5">
                        <div class="row mt-5">
                            <div class="col-md-12 text-right">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle shadow-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <!-- print and add issue -->
                                        <a type="button" class="dropdown-item" href="#"
                                        data-toggle="modal" data-target="#exampleModalLong">
                                            <i class="fas fa-print"></i> Add Issue
                                        </a>
                                        <a type="button" class="dropdown-item" href="{{ route('fabric-issue.print', $fabric_requisition->id) }}" target="_blank">
                                            <i class="fas fa-plus"></i> Print
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <table>
                                    <thead>
                                        <tr style="font-weight:700; font-size:20px;">
                                            <td>NO</td>
                                            <td>:</td>
                                            <td>{{ $fabric_requisition->serial_number }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="text-left">
                                    <tbody class="align-top">
                                        <tr>
                                            <td>GL</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->gl_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Table No</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->table_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->style }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric P/O</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->fabric_po }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Laying Sheet</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->no_laying_sheet}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table>
                                    <tbody class="align-top">
                                        <tr>
                                            <td>Fabric Type</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->fabric_type }}</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->color }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantity Required</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->quantity_required }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantity Issued</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->quantity_issued }}</td>
                                        </tr>
                                        <tr>
                                            <td>Difference</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->difference }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-3">

                    <div class="content-title text-center">
                        <h3>Fabric Issue</h3>
                    </div>
                    <table id="fabric_issue_table" class="table table-bordered table-hover" style="width:100% !important; margin-top: 1rem;">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">Roll No</th>
                                <th scope="col" class="">Weight</th>
                                <th scope="col" class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($fabric_issues->count() > 0)
                                @foreach($fabric_issues as $fabric_issue)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $fabric_issue->roll_no }}</td>
                                    <td>{{ $fabric_issue->weight }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger" onclick="delete_fabricIssue({{ $fabric_issue->id }})">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No Data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('fabric-issue.index') }}" class="btn btn-secondary">Back</a>
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
        const delete_url ='{{ route("fabric-issue.destroy",":id") }}';

        async function delete_fabricIssue(fabric_issue_id) {

            data = { title: "Are you sure?" };
            let confirm_delete = await swal_delete_confirm(data);
            if(!confirm_delete) { return false; };

            let url_delete = delete_url.replace(':id',fabric_issue_id);
            let data_params = { token };

            result = await delete_using_fetch(url_delete, data_params)
            if(result.status == "success"){
                swal_info({
                    title : result.message,
                    reload_option: true, 
                });
            } else {
                swal_failed({ title: result.message });
            }
        };

</script>

<script type="text/javascript">
    const fabric_requisition_id = '{{ $fabric_requisition->id }}';
@endpush