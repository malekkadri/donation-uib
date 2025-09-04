@extends('admin.auth.layouts.app')
@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><i class="fa fa-credit-card"></i> Donation</h1>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Oops!</strong> Please clear below errors:
            <ul>{!! implode('', $errors->all('<li>:message</li>')) !!}</ul>
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Oops!</strong> {{ Session::get('error') }}
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-6">
            <select id="sortby" class="form-select sortby" aria-label="Default select example">
                <option value="paid">paid</option>
                <option value="unpaid">unpaid</option>
                <option value="failed">failed</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="donation-datatable table table-striped table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Recieved on</th>
                                <th>Leaderboard Status</th>
                                <th>Delete/Change Leaderboard Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- Main DataTables --}}
    <script src="{{ asset('vendor/datatables-bs-5/DataTables-1.11.3/js/dataTables.bootstrap5.min.js') }}"></script>

    {{-- Export buttons --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(function () {
            loadTable(".donation-datatable");
        });

        function loadTable(table, data = {}) {
            $(table).DataTable({
                ajax: {
                    url: '{{ route('auth.donation') }}',
                    data: data,
                },
                processing: true,
                serverSide: true,
                scrollX: true,
                destroy: true,
                stateSave: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-csv"></i> Export CSV',
                        className: 'btn btn-outline-primary btn-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf"></i> Export PDF',
                        className: 'btn btn-outline-danger btn-sm',
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn btn-outline-secondary btn-sm'
                    }
                ],
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        render: function (data, type, row) {
                            let email = row.email ? '<br>' + row.email : '';
                            let mobile = row.mobile ? '<br>' + row.mobile : '';
                            return data ? '<span class="name">' + data + email + mobile + '</span>' : email + mobile;
                        }
                    },
                    {
                        data: 'street_address',
                        name: 'street_address',
                        render: function (data) {
                            return data ? '<span class="street_address">' + data + '</span>' : '---';
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function (data) {
                            return data ? '<span class="amount">' + data + '</span>' : '';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function (data) {
                            let bg = 'bg-warning';
                            if (data == 'paid') bg = 'bg-success';
                            else if (data == 'failed') bg = 'bg-danger';
                            return data ? '<span class="status badge ' + bg + '">' + data + '</span>' : '';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function (data) {
                            return getFormattedDateTime(data);
                        }
                    },
                    {
                        data: 'add_to_leaderboard',
                        name: 'add_to_leaderboard',
                        render: function (data) {
                            let bg = data == 'yes' ? 'bg-success' : 'bg-danger';
                            return data ? '<span class="add_to_leaderboard badge ' + bg + '">' + data + '</span>' : '<span class="add_to_leaderboard badge bg-danger">no</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ],
                drawCallback: function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (el) {
                        return new bootstrap.Tooltip(el);
                    });
                },
                order: [[4, 'desc']],
            });
        }

        $(document).on("click", ".change-leaderboard-status", function () {
            if (!confirm("Are you sure, you want to perform this action?")) return false;

            let btn = $(this);
            let id = btn.data("id");
            let status = btn.data('status') == 'yes' ? "yes" : "no";

            btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>");

            $.ajax({
                url: "{{ route('auth.donation.leaderboard-status') }}",
                type: "POST",
                data: {
                    id,
                    status,
                    _token: "{{ csrf_token() }}",
                    _method: "PUT"
                },
                success: function (response) {
                    alert(response.success || response.error || 'Unknown error');
                    $(".donation-datatable").DataTable().ajax.reload(null, false);
                },
                error: function (response) {
                    let msg = "Something went wrong.";
                    if (response.responseJSON?.errors) {
                        msg = $.map(response.responseJSON.errors, value => value).join("\n");
                    } else if (response.responseJSON?.error) {
                        msg = response.responseJSON.error;
                    }
                    alert(msg);
                },
                complete: function () {
                    btn.prop("disabled", false).html('<i class="fa fa-check"></i>');
                }
            });
        });

        $(document).on("click", ".deletedonation", function () {
            if (!confirm("Are you sure, you want to perform this action?")) return false;

            let btn = $(this);
            let id = btn.data("id");

            btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i>");

            $.ajax({
                url: "{{ route('auth.donation.delete') }}",
                type: "POST",
                data: {
                    id,
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                },
                success: function (response) {
                    alert(response.success || response.error || 'Unknown error');
                    $(".donation-datatable").DataTable().ajax.reload(null, false);
                },
                error: function (response) {
                    let msg = "Something went wrong.";
                    if (response.responseJSON?.errors) {
                        msg = $.map(response.responseJSON.errors, value => value).join("\n");
                    } else if (response.responseJSON?.error) {
                        msg = response.responseJSON.error;
                    }
                    alert(msg);
                },
            });
        });
    </script>
@endsection
