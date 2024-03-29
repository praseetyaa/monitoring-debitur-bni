@extends('faturhelper::layouts/admin/main')

@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/style-admin.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('/datepicker')}}/datepicker.min.css"/>
<!-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Bootstrap-4-4.6.0/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/DataTables-1.13.1/css/dataTables.bootstrap4.min.css"/> -->
<!-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Select-1.5.0/css/select.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/datatablescustom.css"/> -->

    <!-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/jquery.dataTables.css"/> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Buttons-2.3.3/css/buttons.bootstrap4.min.css"/> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/FixedColumns-4.2.1/css/fixedColumns.bootstrap4.min.css"/> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/datatablescustom.css"/> -->

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
                                        <th class="nowrap hiddennotif" style="display: none;">Inputer</th>
                                        <th class="nowrap hiddennotif" style="display: none;">Npp</th>
                                        <th class="nowrap">Debitur</th>
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
                            <small class="text-white">Tindak Lanjut Solicit</small>
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
                            <small class="text-white">Tindak Lanjut Prospect</small>
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
                            <small class="text-white">Tindak Lanjut Pipeline </small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="p-1 flex-even align-items-stretch">
                <a class="text-decoration-none" href="{{route('CloseDeb')}}">
                    <div class="card" style="background-color: #900c3e">
                        <div class="card-body">
                            <h5 class="fw-bold text-white" style="margin-bottom:0!important; padding-bottom:0!important">Booking</h5>
                            <h1 class="fw-bold text-white">{{count($jumlahclose)}}</h1>
                            <small class="text-white">Jumlah Booking</small>
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
                            <small class="text-white">Jumlah Data Ditolak</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

<!-- ////////////////////////////////////////// MONITORING ////////////////////////////////////////// -->
@if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('monitoring') || Auth::user()->role_id == role('admin') || Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator'))
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="padding-bottom: 0!important">
                    <h4><i class="bi bi-person"></i> Monitoring Pengguna</h4>
                </div>
                <div class="card-body">
                <div class="row mb-4">
                        <div class="col-md-6 mb-2">
                            <label class="mb-2" style="font-weight: bold">Tanggal Awal</label>
                            <input required value="{{$startd != '' && $startd != 'null' ? $startd : ''}}" class="form-control datepicker" id="startd" placeholder="DD/MM/YYYY">
                        </div>
                        <div class="col-md-6 mb-2 mb-md-3">
                            <label class="mb-2" style="font-weight: bold">Tanggal Akhir</label>
                            <input required value="{{$endd != '' && $endd != 'null' ? $endd : ''}}" class="form-control datepicker" id="endd" placeholder="DD/MM/YYYY">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="mb-2" style="font-weight: bold">Unit / Cabang</label>
                            <select required {{(Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? 'disabled' : '')}} id="cabang" class="form-select">
                                <option value="" {{$cabang == '' ? 'selected' : ''}}>Semua Unit / Cabang</option>
                                @foreach($DCabang as $c)
                                    <option value="{{ $c->id }}" {{$cabang == $c->id ? 'selected' : ''}}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="mb-2" style="font-weight: bold">Role</label>
                            <select required id="role" class="form-select">
                                <option value="" {{$role == '' ? 'selected' : ''}}>Semua Role</option>
                                @foreach($DRoles as $c)
                                    <option value="{{ $c->id }}" {{$role == $c->id ? 'selected' : ''}}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="mb-2" style="font-weight: bold">Tim</label>
                            <select required {{(Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? 'disabled' : '')}} id="tim" class="form-select">
                                <option value="" {{$tim == '' ? 'selected' : ''}}>Semua Tim</option>
                                @foreach($DTim as $c)
                                    <option value="{{ $c->id }}" {{$tim == $c->id ? 'selected' : ''}}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 text-center">
                            <label class="mb-2" style="font-weight: bold">&nbsp;</label><br>
                            <a onclick="setfilter()" class="btn btn-sm btn-secondary mr-2"><i class="bi bi-filter-square"></i> Filter Data</a>
                            <a onclick="resetfilter()" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Reset Filter</a>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function(){
                            $('.datepicker').datepicker({
                                format: 'dd-mm-yyyy',
                            });
                        })

                        function resetfilter()
                        {
                            var NewUrl = "<?= URL::to('dashboard/') ?>"
                            window.location.href = NewUrl
                        }
                        function setfilter()
                        {
                            var tahun = $('#tahun').val() != '' ?  $('#tahun').val() : null
                            var start = $('#startd').val() != '' ?  $('#startd').val() : null
                            var end = $('#endd').val() != '' ?  $('#endd').val() : null
                            var role = $('#role').val() != '' ?  $('#role').val() : null
                            var cabang = $('#cabang').val() != '' ?  $('#cabang').val() : null
                            var tim = $('#tim').val() != '' ?  $('#tim').val() : null

                            var NewUrl = "<?= URL::to('dashboard/"+tahun+"/"+start+"/"+end+"/"+cabang+"/"+tim+"/"+role+"') ?>"
                            window.location.href = NewUrl
                        }
                    </script>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="datatablexxx">
                            <thead class="bg-light">
                                <tr>
                                    <th width="30">No</th>
                                    <th class="nowrap">Nama</th>
                                    <th class="nowrap">Username</th>
                                    <th class="nowrap">Role</th>
                                    <th class="nowrap">Unit / Cabang</th>
                                    <th class="nowrap">Tim</th>
                                    <th class="nowrap">Jabatan</th>
                                    <th class="nowrap">Total Input</th>
                                    <th class="nowrap">Verif Solicit</th>
                                    <th class="nowrap">Total Solicit</th>
                                    <th class="nowrap">Total Prospect</th>
                                    <th class="nowrap">Total Pipeline</th>
                                    <th class="nowrap">Total Reject</th>
                                    <th class="nowrap">Nominal Pencairan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user as $index=>$a)
                                <tr>
                                    <td class="text-center">{{$index+1}}</td>
                                    <td class="nowrap">{{ $a->name }}</td>
                                    <td class="nowrap">{{ $a->username }}</td>
                                    <td class="nowrap">{{ $a->role->name }}</td>
                                    <td class="nowrap">{{ $a->attribute->cabang->nama }}</td>
                                    <td class="nowrap">{{ $a->attribute->tim->nama }}</td>
                                    <td class="nowrap">{{ $a->attribute->jabatan->nama }}</td>
                                    <td class="text-center nowrap">
                                        @if($a->datainput_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURLMon('{{ $a->id }}', '1', '{{$startd}}', '{{$endd}}')">
                                                {{$a->datainput_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center nowrap">
                                        @if($a->dataverif_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURLMon('{{ $a->id }}', '2', '{{$startd}}', '{{$endd}}')">
                                                {{$a->dataverif_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center nowrap">
                                        @if($a->dataapp_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURLMon('{{ $a->id }}', '3', '{{$startd}}', '{{$endd}}')">
                                                {{$a->dataapp_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center nowrap">
                                        @if($a->dataapppros_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURLMon('{{ $a->id }}', '4', '{{$startd}}', '{{$endd}}')">
                                                {{$a->dataapppros_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center nowrap">
                                        @if($a->totalpipeline_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURLMon('{{ $a->id }}', '5', '{{$startd}}', '{{$endd}}')">
                                                {{$a->totalpipeline_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center nowrap">
                                        @if($a->datainputrejected_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURLMon('{{ $a->id }}', 'rejected', '{{$startd}}', '{{$endd}}')">
                                                {{$a->datainputrejected_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="nowrap">{{ $a->total_nom }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
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
    {{-- //////////////////////////////////////////// MONITORING PENCAIRAN //////////////////////////////////////////// --}}
        <div class="col-lg-6">
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
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: "Monitoring Booking Debitur Tahun {{$tahun}}"
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
                            column: {
                                shadow: false,
                                dataLabels: {
                                    enabled: true
                                }
                            },
                        },
                        series: [{
                            name  : 'Dana Cair',
                            data  : @json($danacair),
                            color :'#c70039'
                        }],
                        tooltip: { enabled: false },
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
    {{-- //////////////////////////////////////////// JUMLAH SEKTOR //////////////////////////////////////////// --}}
        <div class="col-lg-6">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="sektor" style="height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    Highcharts.chart('sektor', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        tooltip: {
                            headerFormat: '',
                            pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
                            'Jumlah Debitur: <b>{point.y}</b><br/>'
                        },
                        title: {
                            text: 'Sektor Debitur Tahun {{$tahun}}'
                        },
                        subtitle: {
                            text: 'Akumulasi Jumlah sektor dari para debitur tahun {{$tahun}}'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y:1f}'
                                },
                                showInLegend: true
                            },
                        },
                        series: [{
                            innerSize: '50%',
                            name: 'Sektor Debitur',
                            colorByPoint: true,
                            data: @json($datasektor),
                        }]
                    });
                })
            </script>
        </div>
    {{-- //////////////////////////////////////////// MONITORING DATA //////////////////////////////////////////// --}}
        <div class="col-lg-12">

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
                        chart: {
                            type: 'column'
                        },
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
                                shadow: false,
                                dataLabels: {
                                    enabled: true
                                }
                            },
                        },
                        series: [{
                            name  : 'Total Input',
                            data  : @json($dtsolicit),
                            color : '#ebb501',
                        },{
                            name  : 'Total Solicit',
                            data  : @json($dtsolicitapp),
                            color : '#ff5733',
                        },{
                            name  : 'Total Prospek',
                            data  : @json($dtprospect),
                            color : '#c70039',
                        },{
                            name  : 'Booking',
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
                <h4><i class="bi bi-megaphone"></i> Pengumuman</h4>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control " placeholder="Cari Pengumuman" id="search_pengumuman" >
                    <div class="input-group-append ">
                        <span class="input-group-text" id="basic-addon2"><i class="bi bi-search"></i></span>
                    </div>
                </div>
                <table class="table table-sm table-borderless" id="datatablePengumuman" style="table-layout:fixed;">
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
                let table = new DataTable('#datatablePengumuman', {
                        scrollX: false,
                        scrollCollapse: true,
                        paging: true,
                        bFilter: true,
                        bInfo: false,
                        aaSorting: [],
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
    <script>
        $(document).ready(function(){
            setTimeout(function() {
                table = new DataTable('#datatablexxx', {
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    fixedColumns:
                    {
                        leftColumns: 0,
                        rightColumns:0
                    },
                    bFilter: true,
                    bInfo: true,
                    dom: 'Blfrtip',
                    responsive: false,
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
            }, 1000);
        })
    </script>
    <script type="text/javascript" src="{{asset('/dttables')}}/DataTables-1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/DataTables-1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Select-1.5.0/js/dataTables.select.min.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/highcharts.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/modules/timeline.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/modules/exporting.js"></script>
	<script type="text/javascript" src="{{asset('/')}}hc/code/modules/export-data.js"></script>
    <script type="text/javascript" src="{{asset('/datepicker')}}/datepicker.min.js"></script>
    <script>
        function OpenURLMon(id, status, startd, endd)
        {
            var NewUrl = "<?= URL::to('daftarmonitoring/"+id+"/"+status+"/"+startd+"/"+endd+"') ?>"
            window.location.href = NewUrl
        }
    </script>
@endsection
