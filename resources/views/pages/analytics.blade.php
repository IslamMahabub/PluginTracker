@extends('layouts.app')
@section('title') Analytics @endsection

@section('content')
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Analytics</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"></h2>
                    <div class="table-responsive table--no-card m-b-40">
                        <form method="post" action="{{ route('analytics') }}" onsubmit="return checkDate()" >
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
            @endif
        </div>

        <div class="row">
                <div class="col-xl-6">
                    <div class="au-card chart-percent-card" style="padding-right: 5px">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">Activation / Deactivation (%)</h3>
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div id="activate-deactivate-legend" class="chart-legend"></div>
                                </div>
                                <div class="col-md-8" style="padding-left: 10px">
                                    <div class="percent-chart">
                                        <canvas id="activate-deactivate-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="au-card chart-percent-card" style="padding-right: 5px">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">Deactivation Reason</h3>
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div id="deactivate-reason-legend" class="chart-legend"></div>
                                </div>
                                <div class="col-md-8" style="padding-left: 10px">
                                    <div class="percent-chart">
                                        <canvas id="deactivate-reason-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="au-card chart-percent-card" style="padding-right: 5px">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">Plugin Version (%)</h3>
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div id="plugin-version-legend" class="chart-legend"></div>
                                </div>
                                <div class="col-md-8" style="padding-left: 10px">
                                    <div class="percent-chart">
                                        <canvas id="plugin-version-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="au-card chart-percent-card" style="padding-right: 5px">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">Multisite (Yes/No)</h3>
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div id="multisite-legend" class="chart-legend"></div>
                                </div>
                                <div class="col-md-8" style="padding-left: 10px">
                                    <div class="percent-chart">
                                        <canvas id="multisite-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="au-card chart-percent-card" style="padding-right: 5px">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">Wordpress Version (%)</h3>
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div id="wp-legend" class="chart-legend"></div>
                                </div>
                                <div class="col-md-8" style="padding-left: 10px">
                                    <div class="percent-chart">
                                        <canvas id="wordpress-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="au-card chart-percent-card" style="padding-right: 5px">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">MySQL Version (%)</h3>
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div id="mysql-legend" class="chart-legend"></div>
                                </div>
                                <div class="col-md-8" style="padding-left: 10px">
                                    <div class="percent-chart">
                                        <canvas id="mysql-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="au-card chart-percent-card" style="padding-right: 5px">
                        <div class="au-card-inner">
                            <h3 class="title-2 tm-b-5">Language</h3>
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div id="language-legend" class="chart-legend"></div>
                                </div>
                                <div class="col-md-8" style="padding-left: 10px">
                                    <div class="percent-chart">
                                        <canvas id="language-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
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

        let ActivateDeactivateChart = new Chart(document.getElementById("activate-deactivate-chart"), {
            type: 'doughnut',
            data: {
                labels: [<?php echo (!empty($activeInactiveRate)) ? implode(',', $activeInactiveRate['level']) : '' ?>],
                datasets: [
                    {
                        label: "Activation/Deactivation (%)",
                        backgroundColor: [<?php echo implode(',', $activeInactiveRate['color']) ?>],
                        data: [<?php echo implode(',', $activeInactiveRate['data']) ?>]
                    }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: 'Plugin Activation Deactivation Percentage'
                },
                legend: {
                    display: false,
                    position: 'left'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return data.labels[tooltipItems.index] +
                                " : " +
                                data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                                '%';
                        }
                    }
                }
            }
        });
        document.getElementById('activate-deactivate-legend').innerHTML = ActivateDeactivateChart.generateLegend();

        let DeactivateReasonChart = new Chart(document.getElementById("deactivate-reason-chart"), {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', $reasonWiseUninstall['level']) ?>],
                datasets: [
                    {
                        label: "Deactivation Reason",
                        backgroundColor: [<?php echo implode(',', $reasonWiseUninstall['color']) ?>],
                        data: [<?php echo implode(',', $reasonWiseUninstall['data']) ?>]
                    }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: 'Plugin Deactivation Reason'
                },
                legend: {
                    display: false,
                    position: 'left'
                }

            }
        });
        document.getElementById('deactivate-reason-legend').innerHTML = DeactivateReasonChart.generateLegend();

        let PluginVersionChart = new Chart(document.getElementById("plugin-version-chart"), {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', $versionDownload['version']) ?>],
                datasets: [
                    {
                        label: "Plugin Version (%)",
                        backgroundColor: [<?php echo implode(',', $versionDownload['color']) ?>],
                        data: [<?php echo implode(',', $versionDownload['data']) ?>]
                    }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: 'Plugin Version Percentage'
                },
                legend: {
                    display: false,
                    position: 'left'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return data.labels[tooltipItems.index] +
                                " : " +
                                data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                                '%';
                        }
                    }
                }
            }
        });
        document.getElementById('plugin-version-legend').innerHTML = PluginVersionChart.generateLegend();

        let MultiSiteChart = new Chart(document.getElementById("multisite-chart"), {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', $multiSiteWiseUninstall['level']) ?>],
                datasets: [
                    {
                        label: "Multisite",
                        backgroundColor: [<?php echo implode(',', $multiSiteWiseUninstall['color']) ?>],
                        data: [<?php echo implode(',', $multiSiteWiseUninstall['data']) ?>]
                    }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: 'Multisite Number'
                },
                legend: {
                    display: false,
                    position: 'left'
                }

            }
        });
        document.getElementById('multisite-legend').innerHTML = MultiSiteChart.generateLegend();

        let languageChart = new Chart(document.getElementById("language-chart"), {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', $languageWiseUninstall['language']) ?>],
                datasets: [
                    {
                        label: "Deactivation Language",
                        backgroundColor: [<?php echo implode(',', $languageWiseUninstall['color']) ?>],
                        data: [<?php echo implode(',', $languageWiseUninstall['data']) ?>]
                    }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: 'Plugin Deactivation Language'
                },
                legend: {
                    display: false,
                    position: 'left'
                },
            }
        });
        document.getElementById('language-legend').innerHTML = languageChart.generateLegend();

        let WPChart = new Chart(document.getElementById("wordpress-chart"), {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', $wordpressVersionStatsData['version']) ?>],
                datasets: [
                    {
                        label: "Wordpress Version (%)",
                        backgroundColor: [<?php echo implode(',', $wordpressVersionStatsData['color']) ?>],
                        data: [<?php echo implode(',', $wordpressVersionStatsData['data']) ?>]
                    }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: 'Wordpress Version Percentage'
                },
                legend: {
                    display: false,
                    position: 'left'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return data.labels[tooltipItems.index] +
                                " : " +
                                data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                                '%';
                        }
                    }
                }
            }
        });
        document.getElementById('wp-legend').innerHTML = WPChart.generateLegend();

        let MysqlChart = new Chart(document.getElementById("mysql-chart"), {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', $mysqlVersionStatsData['version']) ?>],
                datasets: [
                    {
                        label: "MySQL Version (%)",
                        backgroundColor: [<?php echo implode(',', $mysqlVersionStatsData['color']) ?>],
                        data: [<?php echo implode(',', $mysqlVersionStatsData['data']) ?>]
                    }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: 'MySQL Version Percentage'
                },
                legend: {
                    display: false,
                    position: 'left'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return data.labels[tooltipItems.index] +
                                " : " +
                                data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                                '%';
                        }
                    }
                }
            }
        });
        document.getElementById('mysql-legend').innerHTML = MysqlChart.generateLegend();

    </script>
@endsection