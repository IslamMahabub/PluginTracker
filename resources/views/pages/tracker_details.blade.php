@extends('layouts.app')
@section('title') Tracking Details @if($header) - Search @endif @endsection

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Tracking Details</h2>
                    </div>
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>theme</th>
                                <th>client</th>
                                <th>version</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($details_sl = 1 )
                            @foreach($tracker_details as $tracking_result)
                                <?php
                                $result = json_decode($tracking_result->log);
                                $server = $result->server;
                                $wp = $result->wp;
                                $active_plugins = $result->active_plugins;
                                $inactive_plugins = $result->inactive_plugins;
                                $extra = $result->extra;
                                ?>
                                <tr data-toggle="collapse" data-target="#{{ $tracking_result->id }}">
                                    <td>{{ $details_sl }}</td>
                                    <td>{{ date('d-m-Y h:m:i A', strtotime($tracking_result->created_at)) }}</td>
                                    <td>{{ $result->ip_address }}</td>
                                    <td>{{ $result->theme }}</td>
                                    <td>{{ $result->client }}</td>
                                    <td>{{ $result->version }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="padding: 5px">
                                        <div id="{{ $tracking_result->id }}" class="col-lg-12 collapse">
                                            <div class="row">
                                                <div class="col-lg-4 tracking-details">
                                                    <div class="top-campaign">
                                                        <h3 class="title-3 m-b-30">server</h3>
                                                        <div class="table-responsive table-data">
                                                            <table class="table table-top-campaign">
                                                                <tbody>
                                                                @if($server)
                                                                    @foreach($server as $key => $value)
                                                                        <tr>
                                                                            <td>{{ $key }}</td>
                                                                            <td class="text-right">{{ $value }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" style="color: red; text-align: center">No Server Info</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 tracking-details">
                                                    <div class="top-campaign">
                                                        <h3 class="title-3 m-b-30">WP Config</h3>
                                                        <div class="table-responsive table-data">
                                                            <table class="table table-top-campaign">
                                                                <tbody>
                                                                @if($wp)
                                                                    @foreach($wp as $key => $value)
                                                                        <tr>
                                                                            <td>{{ $key }}</td>
                                                                            <td class="text-right">{{ $value }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" style="color: red; text-align: center">No WP Config</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 tracking-details">
                                                    <div class="top-campaign">
                                                        <h3 class="title-3 m-b-30">Plugin Extra Data</h3>
                                                        <div class="table-responsive table-data">
                                                            <table class="table table-top-campaign">
                                                                <tbody>
                                                                @if($extra)
                                                                    @foreach($extra as $key => $value)
                                                                        <tr>
                                                                            <td>{{ $key }}</td>
                                                                            <td class="text-right">{{ (is_object($value) ? '0' : $value ) }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" style="color: red; text-align: center">No Extra Data</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 tracking-details">
                                                    <div class="user-data m-b-30">
                                                        <h3 class="title-3 m-b-30"><i class="fa fa-check"></i>Active Plugin ({{ (!empty($active_plugins)) ? count((array) $active_plugins) : 0 }})</h3>
                                                        <div class="table-responsive table-data">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <td>sl</td>
                                                                    <td>name</td>
                                                                    <td>author</td>
                                                                    <td>url</td>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if($active_plugins)
                                                                    @php($sl = 1)
                                                                    @foreach($active_plugins as $active_plugins_result)
                                                                        <tr>
                                                                            <td> {{ $sl }} </td>
                                                                            <td>
                                                                                <div class="table-data__info">
                                                                                    <h6>{{ $active_plugins_result->name }}</h6>
                                                                                    <span style="font-size: 12px" >Version {{ $active_plugins_result->version }}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                {{ $active_plugins_result->author }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $active_plugins_result->plugin_uri }}
                                                                            </td>
                                                                        </tr>
                                                                        @php($sl++)
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" style="color: red; text-align: center">No Active Plugin</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 tracking-details">
                                                    <div class="user-data m-b-30">
                                                        <h3 class="title-3 m-b-30"><i class="fa fa-times"></i>Inactive Plugin ({{ (!empty($inactive_plugins)) ? count((array) $inactive_plugins) : 0 }})</h3>
                                                        <div class="table-responsive table-data">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <td>SL</td>
                                                                    <td>name</td>
                                                                    <td>author</td>
                                                                    <td>url</td>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if($inactive_plugins)
                                                                    @php($sl = 1)
                                                                    @foreach($inactive_plugins as $inactive_plugins_result)
                                                                        <tr>
                                                                            <td> {{ $sl }} </td>
                                                                            <td>
                                                                                <div class="table-data__info">
                                                                                    <h6>{{ $inactive_plugins_result->name }}</h6>
                                                                                    <span style="font-size: 12px" >Version {{ $inactive_plugins_result->version }}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                {{ $inactive_plugins_result->author }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $inactive_plugins_result->plugin_uri }}
                                                                            </td>
                                                                        </tr>
                                                                        @php($sl++)
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" style="color: red; text-align: center">No Inactive Plugin</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @php($details_sl ++)
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
    </script>
@endsection