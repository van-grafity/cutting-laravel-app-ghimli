@extends('layouts.master')

@section('title', 'Create Cut Piece Stock')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary mt-2">
                    <div class="card-header">
                        <div class="card-title">Add Stock In</div>
                    </div>
                    <div class="card-body">
                        <!-- START FORM -->
                        <form action="" method="POST" onsubmit="stopFormSubmission(event)" nethod="POST"
                            class="custom-validation" enctype="multipart/form-data" id="form_create_bundle_stock">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="serial_number" class="form-label">No. Cutting Ticket Serial Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mr-2" id="serial_number" name="serial_number">
                                    <button class="btn btn-primary shadow-sm" id="submit_form">Search Serial Number</button>
                                </div>

                            </div>
                            {{-- <div class="form-group col-md-6">
                                    <label for="location" class="form-label">Location</label>
                                    <select class="form-control" id="location" name="location" style="width: 100%;" data-placeholder="Choose Location">
                                        <option value="">Choose Location</option>
                                        @foreach ($location as $locations)
                                            <option value="{{ $locations->id }}">{{ $locations->location }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            <div class="form-group">
                            </div>
                        </form>
                        <!-- END FORM -->
                    </div>
                </div>

                <!-- Table -->
                <div class="card p-3">
                    <h4>List Scanned Ticket :</h4>
                    <table ble class="table table-sm table-bordered text-center" id="stock-in-table">
                        <thead>
                            <tr>
                                <th scope="col">No Ticket Number</th>
                                <th scope="col">No Serial Number</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Size</th>
                                <th scope="col">Color</th>
                                <th scope="col">Layer</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="row m-1">
                       <h5 class="mr-2">Total </h5>
                       <h5 id="total-stock-in">: 0</h5>
                    </div>
                    <div class="form-group col-md-6 my-4">
                        <label for="location" class="form-label">Location</label>
                        <select class="form-control" id="location" name="location" style="width: 100%;"
                            data-placeholder="Choose Location">
                            <option value="">Choose Location</option>
                            @foreach ($location as $locations)
                                <option value="{{ $locations->id }}">{{ $locations->location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col text-right">
                        <a href="{{ url('/bundle-stock') }}" class="btn btn-secondary shadow-sm">cancel</a>
                        <button class="btn btn-primary btn-md" id="save_stock" onclick="save_stock_in()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script type="text/javascript">
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $('#submit_form').on('click', async function(e) {
            e.preventDefault();
            var serialNumber = $('#serial_number').val();
            if (!serialNumber) {
                return $(document).Toasts('create', {
                    title: `Input Kosong`,
                    body: `No. Cutting Ticket Tidak Boleh Kosong`,
                    class: 'bg-danger',
                    icon: 'fas fa-exclamation-circle',
                    autohide: true,
                    delay: 3000
                })
            }
            if (is_already_inputed(serialNumber)) {
                return $(document).Toasts('create', {
                    title: `Tidak bisa masukin data`,
                    body: `Cutting Ticket dengan Serial Number ${serialNumber} sudah di masuk dalam tabel`,
                    class: 'bg-danger',
                    icon: 'fas fa-exclamation-circle',
                    autohide: true,
                    delay: 3000
                })

            }
            $('#submit_form').prop('disabled', true);
            await get_cutting_ticket(serialNumber);
            $('#submit_form').prop('disabled', false);
            var rowCount = $('#stock-in-table tbody tr').length;
            $('#total-stock-in').text(rowCount)
        })

        const is_already_inputed = (serialNumber) => {
            if ($(`#stock-in-table tbody tr td.serial_number:contains(${serialNumber})`).length > 0) {
                $('#serial_number').val("");
                $('#serial_number').focus();
                return true
            } else {
                return false
            }
        }

        const get_cutting_ticket = async (serial_number) => {
            const fetch_data = {
                url: "{{ route('bundle-stock.store') }}",
                method: "POST",
                data: {
                    serial_number: serial_number,
                    transaction_type: "IN",
                },
                token: token
            };
            const response = await using_fetch_v2(fetch_data);

            if (response.status === "success") {
                $(document).Toasts('create', {
                    title: response.status,
                    body: response.message,
                    icon: 'fas fa-check-circle',
                    class: 'bg-success',
                    autohide: true,
                    delay: 3000
                });
                createForm(response);
            } else {
                $(document).Toasts('create', {
                    title: response.status,
                    body: response.message,
                    class: 'bg-danger',
                    icon: 'fas fa-exclamation-circle',
                    autohide: true,
                    delay: 3000
                })
                $('#serial_number').val("");
                $('#serial_number').focus();
            }
        }

        const createForm = (data) => {
            const response = data.data
            const layingPlanning = response.cutting_order_record.laying_planning_detail.laying_planning;
            const row = `
                <tr id=${response.serial_number}>
                    <th scope="row">${response.ticket_number}</th>
                    <td class="serial_number">${response.serial_number}</td>
                    <td>${layingPlanning.buyer.name}</td>
                    <td>${response.size.size}</td>
                    <td>${layingPlanning.color.color}</td>
                    <td>${response.layer}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="delete_cutting_ticket(this)">Delete</button>
                    </td>
                </tr>
            `
            $("tbody").append(row)
            $('#serial_number').val("");
            $('#serial_number').focus();
        }

        const delete_cutting_ticket = (element) => {
            $(element).parents('tr').remove();
            var rowCount = $('#stock-in-table tbody tr').length;
            $('#total-stock-in').text(rowCount)
        }

        const save_stock_in = async () => {
            $('#save_stock').prop('disabled', true);
            const trElements = document.querySelectorAll('tr');
            let ids = [];

            trElements.forEach((tr) => {
                if (tr.id !== '') {
                    ids.push(tr.id); // Pushing just the ID string
                }
            });

            const fetch_data = {
                url: "{{ route('bundle-stock.store-multiple') }}",
                method: "POST",
                data: {
                    serial_number: ids,
                    transaction_type: "IN",
                    location: $('#location  :selected').val(),
                },
                token: token
            };

            const response = await using_fetch_v2(fetch_data);
            if (response.status === "success") {
                swal_info({
                    title: response.message
                });
                $("#stock-in-table tbody").empty();
                $("#location").prop("selectedIndex", 0)
            } else {
                swal_warning({
                    title: response.message
                })
            }
            $('#save_stock').prop('disabled', false);
        }
    </script>
@endpush
