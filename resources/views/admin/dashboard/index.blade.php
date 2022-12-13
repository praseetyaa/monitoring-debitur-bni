@extends('faturhelper::layouts/admin/main')

@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/style-admin.css') }}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Bootstrap-4-4.6.0/css/bootstrap.min.css"/> --}}
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/DataTables-1.13.1/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Select-1.5.0/css/select.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/datatablescustom.css"/>

<script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>
<style>
    .carddash
    {
        border-radius: 0.8rem;
        box-shadow: 10px 10px 22px -11px rgba(0,0,0,0.75)!important;
        -webkit-box-shadow: 10px 10px 22px -11px rgba(0,0,0,0.75)!important;
        -moz-box-shadow: 10px 10px 22px -11px rgba(0,0,0,0.75)!important;
    }
    .notifshadow
    {
        border-radius: 0.8rem;
        box-shadow: 10px 10px 22px -11px rgba(0,0,0,0.75);
        -webkit-box-shadow: 10px 10px 22px -11px rgba(0,0,0,0.75);
        -moz-box-shadow: 10px 10px 22px -11px rgba(0,0,0,0.75);
    }
    .pengumumanshadow
    {
        border-radius: 0.5rem;
        box-shadow: 10px 10px 11px -11px rgba(0,0,0,0.75);
        -webkit-box-shadow: 10px 10px 11px -11px rgba(0,0,0,0.75);
        -moz-box-shadow: 10px 10px 11px -11px rgba(0,0,0,0.75);
    }
    .table-borderless > tbody > tr > td,
    .table-borderless > tbody > tr > th,
    .table-borderless > tfoot > tr > td,
    .table-borderless > tfoot > tr > th,
    .table-borderless > thead > tr > td,
    .table-borderless > thead > tr > th {
        border: none;
    }
</style>

<div class="alert alert-success" role="alert">
    <div class="alert-message d-flex align-items-center">
        @if(Auth::user()->avatar != '' && File::exists(public_path('assets/images/users/'.Auth::user()->avatar)))
            <img src="{{ asset('assets/images/users/'.Auth::user()->avatar) }}" class="img-fluid bg-white me-3 rounded-circle me-1" alt="{{ Auth::user()->name }}" width="70">
        @else
            <div class="avatar-letter rounded-circle me-3 bg-dark d-flex align-items-center justify-content-center" style="width:4rem; height:4rem">
                <h2 class="text-white mb-0">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</h2>
            </div>
        @endif
        <div>
            <h4 class="alert-heading">Selamat Datang!</h4>
            <p class="mb-0">Selamat datang kembali <strong>{{ Auth::user()->name }}</strong> di {{ config('app.name') }}.</p>
        </div>
    </div>
</div>

{{-- //////////////////////////////////////////// NOTIFICATION //////////////////////////////////////////// --}}
    <div class="row">
        <div class="col-12">
            @if(Auth::user()->role_id == 6)
                @if(count($verifsolicit)>0)
                    <a onclick="verifisolicit()">
                        <div class="alert alert-success shadow" role="alert">
                            <div class="alert-message d-flex align-items-center">
                                <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($verifsolicit)}} Solicit Memerlukan verifikasi dari anda
                            </div>
                        </div>
                    </a>
                @endif
            @endif

            @if(Auth::user()->role_id == 4)
                @if(count($needprospek)>0)
                    <a onclick="needprospek()">
                        <div class="alert alert-success shadow" role="alert">
                            <div class="alert-message d-flex align-items-center">
                                <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($needprospek)}} Prospek memerlukan tindak lanjut dari anda
                            </div>
                        </div>
                    </a>
                @endif

                @if(count($needpipeline)>0)
                    <a onclick="needpipeline()">
                        <div class="alert alert-success shadow" role="alert">
                            <div class="alert-message d-flex align-items-center">
                                <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($needpipeline)}} Pipeline memerlukan tindak lanjut dari anda
                            </div>
                        </div>
                    </a>
                @endif
            @endif

            @if(Auth::user()->role_id == 3)
                @if(count($appsolicit)>0)
                    <a onclick="appisolicit()">
                        <div class="alert alert-success shadow" role="alert">
                            <div class="alert-message d-flex align-items-center">
                                <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($appsolicit)}} Solicit Memerlukan approval dari anda
                            </div>
                        </div>
                    </a>
                @endif

                @if(count($appprospek)>0)
                    <a onclick="appiprospek()">
                        <div class="alert alert-success shadow" role="alert">
                            <div class="alert-message d-flex align-items-center">
                                <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($appprospek)}} Prospek Memerlukan approval dari anda
                            </div>
                        </div>
                    </a>
                @endif


            @endif
        </div>
    </div>
{{-- //////////////////////////////////////////// MODAL SHOW LIST //////////////////////////////////////////// --}}
    <div class="modal fade" id="ModalShowList" tabindex="-1" aria-labelledby="ModalShowListLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="ModalShowListLabel"></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <table class="table table-sm w-100 table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="nowrap hiddennotif" style="display: none;">Nama Input</th>
                                        <th class="nowrap hiddennotif" style="display: none;">Npp</th>
                                        <th class="nowrap">Nama Debitur</th>
                                        <th class="nowrap">sektor</th>
                                    </tr>
                                </thead>
                                <tbody id="BodyModalShowList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function verifisolicit()
        {
            $('#ModalShowListLabel').html('Daftar Solicit Perlu Verifikasi')
            $('.hiddennotif').css('display', '')
            var verifsolicit= @json($verifsolicit);
            var bodytable       = '';
            verifsolicit.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_input']+`</td>
                                    <td>`+val['npp_input']+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }

        function appisolicit()
        {
            $('#ModalShowListLabel').html('Daftar Solicit Perlu Approval')
            $('.hiddennotif').css('display', '')
            var appsolicit= @json($appsolicit);
            var bodytable       = '';
            appsolicit.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_input']+`</td>
                                    <td>`+val['npp_input']+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }


        function appiprospek()
        {
            $('#ModalShowListLabel').html('Daftar Prospek Perlu Approval')
            $('.hiddennotif').css('display', '')
            var appprospek= @json($appprospek);
            var bodytable       = '';
            appprospek.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_input']+`</td>
                                    <td>`+val['npp_input']+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }
        function needprospek()
        {
            $('#ModalShowListLabel').html('Daftar Prospek')
            var needprospek= @json($needprospek);
            var bodytable       = '';
            needprospek.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }

        function needpipeline()
        {
            $('#ModalShowListLabel').html('Daftar Pipeline')
            var needpipeline= @json($needpipeline);
            var bodytable       = '';
            needpipeline.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }



        function OpenURL(url)
        {
            var NewUrl = "<?= URL::to('"+url+"') ?>"
            window.location.href = NewUrl
        }
    </script>
    <style>
        .flex-even {
            flex: 1;
            height: 100%!important:
        }
    </style>
    <div class="counter-task">
        <div class="d-flex flex-sm-row flex-column">
            <div class="p-1 flex-even align-items-stretch">
                <a class="text-decoration-none" href="{{route('DataSol')}}">
                    <div class="card" style="background-color: #ebb501">
                        <div class="card-body">
                            <h5 class="fw-bold text-white" style="margin-bottom:0!important; padding-bottom:0!important">Solicit</h5>
                            <h1 class="fw-bold text-white">{{count($jumlahsolicit)}}</h1>
                            <small class="text-white">Solicit Perlu Tindak Lanjut</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="p-1 flex-even align-items-stretch">
                <a class="text-decoration-none" href="{{route('DataPros')}}">
                    <div class="card" style="background-color: #ff5733">
                        <div class="card-body">
                            <h5 class="fw-bold text-white" style="margin-bottom:0!important; padding-bottom:0!important">Prospect</h5>
                            <h1 class="fw-bold text-white">{{count($jumlahprospek)}}</h1>
                            <small class="text-white">Prospect Perlu Tindak Lanjut</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="p-1 flex-even align-items-stretch">
                <a class="text-decoration-none" href="{{route('DataPipe')}}">
                    <div class="card" style="background-color: #c70039">
                        <div class="card-body">
                            <h5 class="fw-bold text-white" style="margin-bottom:0!important; padding-bottom:0!important">Pipeline</h5>
                            <h1 class="fw-bold text-white">{{count($jumlahpipeline)}}</h1>
                            <small class="text-white">Pipeline Perlu Tindak Lanjut</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="p-1 flex-even align-items-stretch">
                <a class="text-decoration-none" href="{{route('CloseDeb')}}">
                    <div class="card" style="background-color: #900c3e">
                        <div class="card-body">
                            <h5 class="fw-bold text-white" style="margin-bottom:0!important; padding-bottom:0!important">Close</h5>
                            <h1 class="fw-bold text-white">{{count($jumlahclose)}}</h1>
                            <small class="text-white">Jumlah Data Sudah Close</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="p-1 flex-even align-items-stretch">
                <a class="text-decoration-none" href="{{route('RejectDeb')}}">
                    <div class="card" style="background-color: #571845">
                        <div class="card-body">
                            <h5 class="fw-bold text-white" style="margin-bottom:0!important; padding-bottom:0!important">Reject</h5>
                            <h1 class="fw-bold text-white">{{count($jumlahreject)}}</h1>
                            <small class="text-white">Jumlah Data Yang Ditolak</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    
{{-- //////////////////////////////////////////// TAHUN //////////////////////////////////////////// --}}
<div class="row mb-2">
    <div class="col-md-12 mb-2">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon2" style="width:100px">Tahun Data</span>
            </div>
            <select required class="form-control" id="tahun">
                <?php
                    $years = range(date('Y'), 2021);
                    foreach($years as $dt)
                    {
                ?>
                        <option value="<?= $dt ?>"><?= $dt ?></option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#tahun').val('{{$tahun}}')
        $('#tahun').on('change', function(){
            var NewUrl = "<?= URL::to('/dashboard/"+this.value+"') ?>"
            window.location.href = NewUrl
        })
    })
</script>
<div class="row">
    <div class="col-lg-6">
            {{-- //////////////////////////////////////////// MONITORING PENCAIRAN //////////////////////////////////////////// --}}
            <div class="card card-custom mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="Pencairan" style="height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                Highcharts.chart('Pencairan', {
                    title: {
                        text: "Monitoring Pencairan Dana Tahun {{$tahun}}"
                    },
                    subtitle: {
                        text: 'Jumlah dana yang dicairkan setiap bulannya berdasarkan tanggal pencairan pada tahap pipeline'
                    },
                    xAxis: {
                        categories: [
                                    'Jan',
                                    'Feb',
                                    'Mar',
                                    'Apr',
                                    'May',
                                    'Jun',
                                    'Jul',
                                    'Aug',
                                    'Sep',
                                    'Oct',
                                    'Nov',
                                    'Dec'
                                    ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Jumlah'
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    series: [{
                        name  : 'Dana Cair',
                        data  : @json($danacair),
                        color :'#99B898'
                    }],

                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                }
                            }
                        }]
                    }




                });
            })
        </script>
    </div>
    <div class="col-lg-6">
            {{-- //////////////////////////////////////////// MONITORING DATA //////////////////////////////////////////// --}}

        <div class="card card-custom mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="Datadeb" style="height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                Highcharts.chart('Datadeb', {
                    title: {
                        text: "Monitoring Data Debitur Tahun {{$tahun}}"
                    },
                    subtitle: {
                        text: 'monitoring data debitur yang masuk setiap bulannya'
                    },
                    xAxis: {
                        categories: [
                                    'Jan',
                                    'Feb',
                                    'Mar',
                                    'Apr',
                                    'May',
                                    'Jun',
                                    'Jul',
                                    'Aug',
                                    'Sep',
                                    'Oct',
                                    'Nov',
                                    'Dec'
                                    ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Jumlah'
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0,
                            borderWidth: 0,
                            groupPadding: 0,
                            shadow: false
                        }
                    },
                    series: [{
                        name  : 'Input Solicit',
                        data  : @json($dtsolicit),
                        color : '#ebb501',
                    },{
                        name  : 'Solicit App',
                        data  : @json($dtsolicitapp),
                        color : '#ff5733',
                    },{
                        name  : 'Prospek App',
                        data  : @json($dtprospect),
                        color : '#c70039',
                    },{
                        name  : 'Data Close',
                        data  : @json($dtclose),
                        color : '#900c3e',
                    },{
                        name  : 'Data Reject',
                        data  : @json($dtreject),
                        color : '#571845',
                    }],

                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                }
                            }
                        }]
                    }




                });
            })
        </script>
    </div>
</div>

{{-- //////////////////////////////////////////// PENGUMUMAN //////////////////////////////////////////// --}}
    @if(count($pengumuman)>0)
        <div class="card">
            <div class="card-header" style="padding-bottom: 0!important">
                <h3 style="font-weight:bold"><i class="bi bi-megaphone"></i> Pengumuman</h3>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control " placeholder="Cari Pengumuman" id="search_pengumuman" >
                    <div class="input-group-append ">
                        <span class="input-group-text" id="basic-addon2"><i class="bi bi-search"></i></span>
                    </div>
                </div>
                <table class="table table-sm table-borderless" id="datatablexxx" style="table-layout:fixed;">
                    <thead class="d-none">
                        <tr>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengumuman as $item)
                            @php
                                $datas      = base64_encode($item->isi);
                                $juduls     = base64_encode($item->judul);
                            @endphp
                            <tr>
                                <td>
                                    <div class="container_anc div shadow" style="cursor: pointer" onclick="show_pengumuman('{{ $datas }}', '{{$juduls}}')">
                                        <article class="card_anc curve_anc">
                                            <div class="text-center">
                                                @if ($item->thumbnail == null && $item->thumbnail == '')
                                                    <i class="bi bi-card-image"></i>
                                                @else
                                                    <img src="{{ URL::asset('storage/'.$item->thumbnail) }}" alt="image">
                                                @endif
                                            </div>
                                            <div style="text-overflow: ellipsis;overflow: hidden; white-space: nowrap;">
                                                <h5 class="fw-bold" style="padding-bottom: 0!important">{{$item->judul}}</h5>
                                                <div style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap; width:100%; font-size: 12px">
                                                    {{ strip_tags($item->isi) }}
                                                </div>
                                                <span style="font-size: 11px">
                                                    <i class="bi bi-person"></i>
                                                    {{$item->nama_pembuat}} - <time>{{ date('d M Y, H:i:s', strtotime($item->tanggal_pebuatan))}}</time>
                                                </span>
                                            </div>
                                        </article>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                let table = new DataTable('#datatablexxx', {
                        scrollX: false,
                        scrollCollapse: true,
                        paging: true,
                        bFilter: true,
                        bInfo: false,
                        dom: 'rtip',
                        pageLength : 3,
                        lengthMenu: [[3, 6, 9, -1], [3, 6, 9, 'Todos']],
                        responsive: true,
                        buttons: [
                        {
                            extend: 'excel',
                            className: 'exportbtn',
                        },
                        {
                            extend: 'pdf',
                            className: 'exportbtn',
                        },
                        {
                            extend: 'print',
                            className: 'exportbtn',
                        }

                    ]
                });
                $('#search_pengumuman').keyup(function(){
                    table.search($(this).val()).draw() ;
                })
            })
        </script>
        {{-- <main>
            @foreach ($pengumuman as $item)
                @php
                    $datas      = base64_encode($item->isi);
                    $juduls     = base64_encode($item->judul);
                @endphp
                <div class="container_anc div" style="cursor: pointer" onclick="show_pengumuman('{{ $datas }}', '{{$juduls}}')">
                    <article class="card_anc curve_anc shadow">
                    <div class="text-center col-1">
                        @if ($item->thumbnail == null && $item->thumbnail == '')
                            <i class="bi bi-card-image"></i>
                        @else
                            <img src="{{ URL::asset('storage/'.$item->thumbnail) }}" alt="image">
                        @endif
                    </div>
                    <div class="col-11" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;">
                        <h5 class="fw-bold">{{$item->judul}}</h5>
                        <div class="" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap; width:70%">
                            {{ strip_tags($item->isi) }}
                        </div>
                        <span style="font-size: 11px">
                            <i class="bi bi-person"></i>
                            {{$item->nama_pembuat}} - <time>{{ date('d M Y, H:i:s', strtotime($item->tanggal_pebuatan))}}</time>
                        </span>
                    </div>
                    </article>
                </div>
            @endforeach
            <div class="d-flex justify-content-between align-items-center" id="pagination">
                {!! $pengumuman->links() !!}
                <a href="#">Lihat Semua <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </main> --}}

        <div class="modal fade" id="modal_show_pengumuman" tabindex="-1" aria-labelledby="modal_show_pengumuman" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="modal_judul_pengumuman"></h5>
                        <button type="button" class="btn btn-sm btn-primary" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" id="body_show_pengumuman">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function show_pengumuman(data, judul)
            {
                $('#modal_judul_pengumuman').html(atob(judul))
                $('#body_show_pengumuman').html(atob(data));
                $('#modal_show_pengumuman').modal('show');
            }
        </script>
    @endif

@endsection


@section('js')
    <script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>

    <script type="text/javascript" src="{{asset('/dttables')}}/DataTables-1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/DataTables-1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Select-1.5.0/js/dataTables.select.min.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/highcharts.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/modules/timeline.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/modules/exporting.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/modules/export-data.js"></script>
@endsection
