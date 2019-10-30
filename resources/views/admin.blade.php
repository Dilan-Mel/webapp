@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="section__content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">PII-IC Admin </h2>
                        </div>
                    </div>
                </div>
                <div class="row m-t-25">
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c1" style="height: 100%;">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-account-o"></i>
                                    </div>
                                    <div class="text">
                                        <h2>Number of users</h2>
                                        <span>3</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                    <canvas id="widgetChart1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c2" style="height: 100%;">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    </div>
                                    <div class="text">
                                        <h2>43%</h2>
                                        <span>PCI-DSS</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                    <canvas id="widgetChart2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c3" style="height: 100%;">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-calendar-note"></i>
                                    </div>
                                    <div class="text">
                                        <h2>23%</h2>
                                        <span>GDPR</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                    <canvas id="widgetChart3"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c4" style="height: 100%;">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="icon">
                                        <i class="zmdi zmdi-money"></i>
                                    </div>
                                    <div class="text">
                                        <h2>56%</h2>
                                        <span>Overall</span>
                                    </div>
                                </div>
                                <div class="overview-chart">
                                    <canvas id="widgetChart4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="au-card chart-percent-card">
                            <div class="au-card-inner">
                                <h3 class="title-2 tm-b-5">Identified PII%</h3>
                                <div class="row no-gutters">
                                    <div class="col-xl-12">
                                        <div class="chart-note-wrap">
                                            
                                            
                                        </div>

                                       
                                        <table border="1" class="table table-responsive-lg">
                                        <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Identified PII Data</th>
                                            <th>Number of PCI-DSS Data</th>
                                            <th>Number of GDPR Data</th>
                                        <tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($persons as $person) { ?>
                                                <tr>
                                                    <td>{{$person->name}}</td>
                                                    <td>{{$person->identified_strings}}</td>
                                                    <td>{{$person->pci_dss}}</td>
                                                    <td>{{$person->gdpr}}</td>
                                                </tr>    
                                            <?php 
                                        } ?>
                                            </tbody>
                                            </table>
                                    </div>
                                    <!-- <div class="col-xl-6">
                                        <//div class="percent-chart">
                                            <//canvas id="percent-chart"><//canvas>
                                        <//div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 mb-5">
                    <div class="col-md-12">
                        <form action="{{route('process.pii')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>File upload</label>
                                <input type="file" class="form-control-file" name="filepond" id="fileUpload">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" id="submit_submission_btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let token = document.head.querySelector('meta[name="csrf-token"]');

        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginImagePreview
        );

        FilePond.create(document.getElementById('fileUpload'), {
            allowRevert: true,
            allowImagePreview: true,
            instantUpload: true,
            allowMultiple: false,
            allowFileTypeValidation: false,
            // acceptedFileTypes: ['image/png', 'image/jpeg', 'image/bmp', 'application/text', 'application/json', 'application/docx'],
            onremovefile: (file) => {
                // console.log('File removed.');
                let btn = $('#submit_submission_btn');
                btn.removeClass('loading');
                btn.removeClass('disabled');
            },
            onprocessfilestart: (file) => {
                // console.log('File processing started.');
                let btn = $('#submit_submission_btn');
                btn.addClass('loading');
                btn.addClass('disabled');

            },
            onprocessfile: (e, f) => {
                if (e) console.log(e.toString());
                // console.log('File processed.');
                let btn = $('#submit_submission_btn');
                btn.removeClass('loading');
                btn.removeClass('disabled');
            },
            onprocessfileabort: (f) => {
                // console.log('File processing aborted.');
                let btn = $('#submit_submission_btn');
                btn.removeClass('loading');
                btn.removeClass('disabled');
            },
            server: {
                process: {
                    url: '/files/process',
                    method: 'POST',
                    withCredentials: false,
                    headers: {
                        'X-CSRF-TOKEN': token.content
                    },
                    timeout: 1800000, // 30 minutes
                    onload: response => response,
                },
                revert: {
                    url: '/files/delete',
                    headers: {
                        'X-CSRF-TOKEN': token.content
                    },
                    timeout: 10000, // 10 seconds
                }
            }
        });

        var ctx = document.getElementById("percent-chart");
        if (ctx) {
            ctx.height = 280;
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [
                        {
                            label: "My First dataset",
                            data: [ {{$pci}}, {{$gdpr}} ],
                            backgroundColor: [
                                '#00b5e9',
                                '#fa4251'
                            ],
                            hoverBackgroundColor: [
                                '#00b5e9',
                                '#fa4251'
                            ],
                            borderWidth: [
                                0, 0
                            ],
                            hoverBorderColor: [
                                'transparent',
                                'transparent'
                            ]
                        }
                    ],
                    labels: [
                        'PCI DSS',
                        'GDPR'
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    cutoutPercentage: 55,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        titleFontFamily: "Poppins",
                        xPadding: 15,
                        yPadding: 10,
                        caretPadding: 0,
                        bodyFontSize: 16
                    }
                }
            });
        }


    </script>
@endsection
