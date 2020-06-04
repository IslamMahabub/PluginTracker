@extends('layouts.app')
@section('title') Home @endsection

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Overview</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"></h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <form method="post" action="{{ route('home') }}" onsubmit="return checkDate()" >
                            @csrf
                            <table class="table table-borderless table-striped search">
                                <tbody>
                                <tr>
                                    <td><input name="start_date" type="text" id="startdatepicker" value="{{ $startDate }}" placeholder="Enter Start Date" class="form-control" autocomplete="off" ></td>
                                    <td><input name="end_date" type="text" id="enddatepicker" value="{{ $endDate }}" placeholder="Enter End Date" class="form-control" autocomplete="off" ></td>
                                    <td>
                                        <select name="plugins" id="select" class="form-control">
                                            @foreach($plugins as $pluginsResult)
                                                <option value="{{ $pluginsResult->plugin }}" @if($pluginName == $pluginsResult->plugin) selected @endif>{{ $pluginsResult->plugin }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
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
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c4">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="icon">
                                    <i class="fas fa-angle-double-up"></i>
                                </div>
                                <div class="text">
                                    <h2>{{ number_format($activateRate,1) }}%</h2>
                                    <span>Activation Rate</span>
                                </div>
                                <div class="overview-chart">
                                    <canvas id="widgetChart4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c3">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="icon">
                                    <i class="fas fa-angle-double-down"></i>
                                </div>
                                <div class="text">
                                    <h2>{{ number_format($deactivateRate,1) }}%</h2>
                                    <span>Deactivation Rate</span>
                                </div>
                                <div class="overview-chart">
                                    <canvas id="widgetChart3"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c2">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="text">
                                    <h2>{{ number_format($totalActivate, 0) }}</h2>
                                    <span>Activated</span>
                                </div>
                            </div>
                            <div class="overview-chart">
                                <canvas id="widgetChart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="icon">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="text">
                                    <h2>{{ number_format($totalUninstall, 0) }}</h2>
                                    <span>Deactivated</span>
                                </div>
                            </div>
                            <div class="overview-chart">
                                <canvas id="widgetChart1"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <h2 class="title-2 m-b-25">Active Install</h2>
                    <div class="au-card au-card--bg-blue au-card-top-countries m-b-40">
                        <div class="au-card-inner">
                            <div class="table-responsive">
                                <table class="table table-top-countries">
                                    <tbody>
                                    @foreach($totalActiveInstall as $key => $value)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td class="text-right">{{ number_format($value) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h2 class="title-2 m-b-25">Downloads</h2>
                    <div class="au-card au-card--bg-blue au-card-top-countries m-b-40">
                        <div class="au-card-inner">
                            <div class="table-responsive">
                                <table class="table table-top-countries">
                                    <tbody>
                                    @foreach($totalDownload as $key => $value)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td class="text-right">{{ number_format($value) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6" @if(!$installUninstallGraph['date']) style="display: none" @endif>
                    <h2 class="title-2 m-b-25">Activation / Deactivation</h2>
                    <div class="au-card recent-report">
                        <div class="au-card-inner">
                            <div class="recent-report__chart">
                                <canvas id="line-chart-1"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="title-2 m-b-25" style="margin-bottom: 20px">Deactivate Reasons</h2>
                    <div class="table-responsive m-b-40">
                        <table class="table table-borderless table-data3" id="deactivateTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Reason</th>
                                <th style="text-align: center">Deactivate No</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($key = 1)
                            @foreach($reasonWiseUninstall as $result)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{{ ucfirst(str_replace('-', ' ', $result->reason_id)) }}</td>
                                <td style="text-align: center">{{ $result->total_unindtall }}</td>
                            </tr>
                            @php($key++)
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

        $(document).ready( function () {
            $('#deactivateTable').DataTable( {
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false,
                "searching": false
            } );
        } );
        new Chart(document.getElementById("line-chart-1"), {
            type: 'line',
            data: {
                labels: [<?php echo implode(',', $installUninstallGraph['date']) ?>],
                datasets: [
                    {
                    data: [<?php echo implode(',', $installUninstallGraph['install']) ?>],
                    label: "Activation",
                    borderColor: "#63c76a",
                    fill: false
                    },
                    {
                    data: [<?php echo implode(',', $installUninstallGraph['uninstall']) ?>],
                    label: "Deactivation",
                    borderColor: "#fa4251",
                    fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: ''
                }
            }
        });
    </script>
@endsection