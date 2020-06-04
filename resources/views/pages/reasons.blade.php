@extends('layouts.app')
@section('title') Reasons @if($header) - Search @endif @endsection

@section('content')
    <style>
        td.details-control {
            background: #fff url("{{ asset('images/details_open.png') }}") no-repeat center center !important;
            cursor: pointer;
            background-size: 20% !important;
        }
        tr.shown td.details-control {
            background: #fff url("{{ asset('images/details_close.png') }}") no-repeat center center !important;
            background-size: 20% !important;
        }
    </style>
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Reasons</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title-1 m-b-25"></h2>
                        <div class="table-responsive table--no-card m-b-40">
                            <form method="post" action="{{ route('reasons') }}" onsubmit="return checkDate()">
                                @csrf
                                <table class="table table-borderless table-striped search">
                                    <tbody>
                                    <tr>
                                        <td><input name="start_date" type="text" id="startdatepicker" value="{{ $startDate }}" placeholder="Enter Start Date" class="form-control" autocomplete="off" ></td>
                                        <td><input name="end_date" type="text" id="enddatepicker" value="{{ $endDate }}" placeholder="Enter End Date" class="form-control" autocomplete="off" ></td>
                                        <td>
                                            <select name="plugins" id="plugins" class="form-control">
                                                <option value="">All Plugins</option>
                                                @foreach($plugins as $pluginsResult)
                                                    <option value="{{ $pluginsResult->plugin }}" @if($pluginName == $pluginsResult->plugin) selected @endif>{{ $pluginsResult->plugin }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="reason" id="reason" class="form-control">
                                                <option value="">Select Reason</option>
                                                @foreach($reasonList as $reasonResult)
                                                    <option value="{{ $reasonResult->reason_id }}" @if($reason == $reasonResult->reason_id) selected @endif>{{ ucfirst(str_replace('-', ' ', $reasonResult->reason_id)) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td colspan="4" style="text-align: center">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i>&nbsp; Search
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>

                @if($header)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label class=" form-control-label"><b>Plugin:</b></label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <p class="form-control-static">{{ $pluginName }}</p>
                                        </div>
                                    </div>
                                    @if($startDate != '' && $endDate != '')
                                        <div class="row form-group">
                                            <div class="col col-md-3">
                                                <label class=" form-control-label"><b>Date:</b></label>
                                            </div>
                                            <div class="col-12 col-md-9">
                                                <p class="form-control-static">{{ date('d-m-Y', strtotime($startDate)) }} To {{ date('d-m-Y', strtotime($endDate)) }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($reason != '')
                                        <div class="row form-group">
                                            <div class="col col-md-3">
                                                <label class=" form-control-label"><b>Reason:</b></label>
                                            </div>
                                            <div class="col-12 col-md-9">
                                                <p class="form-control-static">{{ ucfirst(str_replace('-', ' ', $reason)) }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endif

            <div class="row m-t-30">
                <div class="col-md-12">
                    <!-- DATA TABLE-->
                    <div class="table-responsive m-b-40">
                        <table id="example" class="table table-borderless table-data3">
                            <thead>
                            <tr>
                                <th style="width: 5%"></th>
                                <th style="width: 10%">Date</th>
                                <th style="width: 12%">Plugin</th>
                                <th style="width: 18%">Reason</th>
                                <th style="width: 45%; text-align: center">Comments</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- END DATA TABLE-->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © {{ date('Y') }} WebAppick. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script>
        $('#startdatepicker').datepicker({
            uiLibrary: 'bootstrap4'
        });
        $('#enddatepicker').datepicker({
            uiLibrary: 'bootstrap4'
        });
        function checkDate(){
            var startDate   = $('#startdatepicker').val();
            var endDate     = $('#enddatepicker').val();
            if(startDate > endDate){
                alert('End Date must be bigger than Start Date');
                $('#startdatepicker').focus();
                return false;
            }
            return true;
        }

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<table  class="table table-borderless table-data3">'+
                '<tr>'+
                '<td>URL:</td>'+
                '<td colspan="5">'+d.url+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>User Email:</td>'+
                '<td colspan="5">'+d.user_email+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>User Name:</td>'+
                '<td colspan="5">'+d.user_name+'</td>'+
                '</tr>'+
                '<td>Software:</td>'+
                '<td colspan="5">'+d.software+'</td>'+
                '</tr>'+
                '<td>PHP Version:</td>'+
                '<td colspan="5">'+d.php_version+'</td>'+
                '</tr>'+
                '<td>MySQL Version:</td>'+
                '<td colspan="5">'+d.mysql_version+'</td>'+
                '</tr>'+
                '<td>WP Version:</td>'+
                '<td colspan="5">'+d.wp_version+'</td>'+
                '</tr>'+
                '<td>Local:</td>'+
                '<td colspan="5">'+d.locale+'</td>'+
                '</tr>'+
                '<td>Multisite:</td>'+
                '<td colspan="5">'+d.multisite+'</td>'+
                '</tr>'+
                '</table>';
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            var start_date = $('#startdatepicker').val();
            var end_date = $('#enddatepicker').val();
            var plugins = $('#plugins').val();
            var reason = $('#reason').val();
            var table = $('#example').DataTable( {
                "ajax": {
                    type: 'POST',
                    url: '{{ url('reasonslist') }}',
                    data:{start_date:start_date, end_date:end_date, plugins:plugins, reason:reason},
                },
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "created_at" },
                    { "data": "plugin" },
                    { "data": "reason_id" },
                    { "data": "reason_info" }
                ],
                "order": []
            } );

            // Add event listener for opening and closing details
            $('#example tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );
        } );
    </script>
@endsection