@extends('layouts.app')
@section('title') Track @if($header) - Search @endif @endsection

@section('content')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Plugin Tracking Data</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title-1 m-b-25"></h2>
                        <div class="table-responsive table--no-card m-b-40">
                            <form method="post" action="{{ route('tracker') }}" onsubmit="return checkDate()">
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
                                        <td colspan="2">
                                            <input class="form-control" type="text" name="search_string" id="search_string" value="" placeholder="Search for site, url, first name, last name &amp; email..." autocomplete="off" />
                                            <input class="form-control" type="hidden" name="search_string_data" id="search_string_data" value="{{ $search_string }}" placeholder="Search for site, url, first name, last name &amp; email..." autocomplete="off" />
                                        </td>
                                        <td style="text-align: center">
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
                                    @if( $startDate != '' && $endDate != '' )
                                        <div class="row form-group">
                                            <div class="col col-md-3">
                                                <label class=" form-control-label"><b>Date:</b></label>
                                            </div>
                                            <div class="col-12 col-md-9">
                                                <p class="form-control-static">{{ date('d-m-Y', strtotime($startDate)) }} To {{ date('d-m-Y', strtotime($endDate)) }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if( $search_string )
                                        <div class="row form-group">
                                            <div class="col col-md-3">
                                                <label class=" form-control-label"><b>Search String:</b></label>
                                            </div>
                                            <div class="col-12 col-md-9">
                                                <p class="form-control-static">{{ $search_string }}</p>
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
                        <table id="trackingTable" class="table table-borderless table-data3">
                            <thead>
                            <tr>
                                <th style="width: 5%"></th>
                                <th style="width: 10%">Date</th>
                                <th style="width: 15%">Plugin</th>
                                <th style="width: 20%">Site</th>
                                <th style="width: 10%; text-align: center">URL</th>
                                <th style="width: 10%; text-align: center">First Name</th>
                                <th style="width: 10%; text-align: center">Last Name</th>
                                <th style="width: 10%; text-align: center">Admin Email</th>
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

    <script src="{{ asset('vendor/highlight/jQuery.highlight.js') }}"></script>
    <script>
        $( document ).ajaxComplete(function() {
            var option = {
                color: "black",
                background: "yellow",
                bold: false,
                class: "high",
                ignoreCase: true,
                wholeWord: false
            }
            @if($search_string)
            $("#trackingTable").find('tbody tr td:not(:first-child, :nth-child(2), :nth-child(3))').highlight('{{ $search_string }}', option);
            @endif
        });
    </script>

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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#search_string').focus();
            var start_date = $('#startdatepicker').val();
            var end_date = $('#enddatepicker').val();
            var plugins = $('#plugins').val();
            var search_string = $('#search_string_data').val();
            $('#trackingTable').DataTable( {
                "ajax": {
                    type: 'POST',
                    url: '{{ url('tracklist') }}',
                    data:{start_date:start_date, end_date:end_date, plugins:plugins, search_string:search_string},
                },
                "columns": [
                    {
                        "orderable":      false,
                        "data":           'details',
                        "defaultContent": ''
                    },
                    { "data": "created_at" },
                    { "data": "plugin" },
                    { "data": "site" },
                    { "data": "url" },
                    { "data": "first_name" },
                    { "data": "last_name" },
                    { "data": "admin_email" }
                ],
                "order": []
            } );
        } );
    </script>
@endsection