@extends('faturhelper::layouts/admin/main')

@section('title', $title)

@section('content')
<style>
    .pointer
    {
        cursor: pointer;
    }
    .swal2-html-container
    {
        overflow: hidden!important;
    }
    .nowrap
    {
        white-space: nowrap!important;
    }
    .form-check-input
    {
        margin-left: 0!important;
    }
</style>

{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css"/> --}}

{{-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Bootstrap-4-4.6.0/css/bootstrap.min.css"/> --}}
{{-- <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/DataTables-1.13.1/css/dataTables.bootstrap4.min.css"/> --}}
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/jquery.dataTables.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Buttons-2.3.3/css/buttons.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/FixedColumns-4.2.1/css/fixedColumns.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/datatablescustom.css"/>
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css" integrity="sha512-YdYyWQf8AS4WSB0WWdc3FbQ3Ypdm0QCWD2k4hgfqbQbRCJBEgX0iAegkl2S1Evma5ImaVXLBeUkIlP6hQ1eYKQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
<link rel="stylesheet" type="text/css" href="{{asset('/datepicker')}}/datepicker.min.css"/>

<script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>
<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">{{$title}}</h1>
    @if(in_array(Auth::user()->role_id, array(1,4,5)) && Request::route()->getName() == 'DataSol')
        <div class="btn-group">
            <a href="{{ route('solicitcreate') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Input Solicit</a>
        </div>
    @endif
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="alert-message">{{ Session::get('message') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(strpos(Request::path() ,'daftarmonitoring') === false)
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="mb-2" style="font-weight: bold">Tanggal Awal</label>
                            <input required value="{{$startd != '' && $startd != 'null' ? $startd : ''}}" class="form-control datepicker" id="startd" placeholder="DD/MM/YYYY">
                            {{-- <input required type="date" value="{{$startd}}" class="form-control" id="startd"> --}}
                        </div>
                        <div class="col-md-3">
                            <label class="mb-2" style="font-weight: bold">Tanggal Akhir</label>
                            <input required value="{{$endd != '' && $endd != 'null' ? $endd : ''}}" class="form-control datepicker" id="endd" placeholder="DD/MM/YYYY">
                            {{-- <input required type="date" value="{{$endd}}" class="form-control" id="endd"> --}}
                        </div>

                        <div class="col-md-3">
                            <label class="mb-2" style="font-weight: bold">Status</label>
                            <select required id="status" class="form-control">
                                <option value="" {{$status == '' ? 'selected' : ''}}>Semua Status Data</option>
                                @foreach($StatusDebitur as $c)
                                    <option value="{{ $c->status_debitur }}" {{$status == $c->status_debitur ? 'selected' : ''}}>{{ $c->narasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-2" style="font-weight: bold">Cabang</label>
                            <select required id="cabang" class="form-control">
                                <option value="" {{$status == '' ? 'selected' : ''}}>Semua Cabang</option>
                                @foreach($DCabang as $c)
                                    <option value="{{ $c->id }}" {{$cabang == $c->id ? 'selected' : ''}}>{{ $c->nama }}</option>
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

                            if("{{Auth::user()->role_id == role('inputer')}}")
                            {
                                $('#cabang').attr('disabled', true)
                                $('#cabang').val('{{Auth::user()->attribute->cabang_id}}')
                            }

                            if("{{Request::route()->getName() != 'DataSol'}}" && "{{Request::route()->getName() != 'DataPros'}}" && "{{Request::route()->getName() != 'MasterData'}}")
                            {
                                $('#status').attr('disabled', true)
                            }

                            if("{{Request::route()->getName() == 'RejectDeb'}}" == "1")
                            {
                                $('#status').append("<option value='' selected>Data Reject</option>")
                            }
                        })
                        function resetfilter()
                        {
                            routename = "{{Request::route()->getName()}}"
                            var NewUrl = "<?= URL::to('"+routename+"/') ?>"
                            window.location.href = NewUrl
                        }
                        function setfilter()
                        {
                            routename = "{{Request::route()->getName()}}"
                            routename = routename.replace('Menu', '')
                            var stard = $('#startd').val() != '' ?  $('#startd').val() : null
                            var endd = $('#endd').val() != '' ?  $('#endd').val() : null
                            var status = $('#status').val() != '' ?  $('#status').val() : null
                            var cabang = $('#cabang').val() != '' ?  $('#cabang').val() : null

                            var NewUrl = "<?= URL::to('"+routename+"/"+stard+"/"+endd+"/"+status+"/"+cabang+"') ?>"
                            window.location.href = NewUrl
                        }
                    </script>
                    <hr>
                    @if(in_array(Auth::user()->role_id, array(1,3,4,5,6)))
                        <div class="row mb-2" id="btnactionall" style="display: none">
                            <div class="col-md-12 text-center">
                                @if(in_array(Auth::user()->role_id, array(6)) && Request::route()->getName() == 'DataSol')
                                    <a onclick="action_all('verif')" class="btn btn-sm btn-primary">Verif Solicit Terpilih</a>
                                    <a onclick="action_all('deny')" class="btn btn-sm btn-warning">Tolak Solicit Terpilih</a>
                                    <a onclick="action_all('delete')" class="btn btn-sm btn-danger mr-2">Hapus Solicit Terpilih</a>
                                @elseif(in_array(Auth::user()->role_id, array(3)) && Request::route()->getName() == 'DataSol')
                                    <a onclick="action_all('app')" class="btn btn-sm btn-primary">Approve Solicit Terpilih</a>
                                    <a onclick="action_all('deny')" class="btn btn-sm btn-warning">Tolak Solicit Terpilih</a>
                                    <a onclick="action_all('delete')" class="btn btn-sm btn-danger mr-2">Hapus Solicit Terpilih</a>
                                @elseif(in_array(Auth::user()->role_id, array(3)) && Request::route()->getName() == 'DataPros')
                                    <a onclick="action_all('apppros')" class="btn btn-sm btn-primary">Approve Prospek Terpilih</a>
                                    <a onclick="action_all('deny')" class="btn btn-sm btn-warning">Tolak Prospek Terpilih</a>
                                @elseif(in_array(Auth::user()->role_id, array(4, 5, 1)))
                                    <a onclick="action_all('delete')" class="btn btn-sm btn-danger mr-2">Hapus Solicit Terpilih</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                <div>
                    <div class="row">
                        <div class="col-md-12 text-left">
                            <small style="font-weight: bold">*Klik kolom untuk melihat detail</small>
                        </div>
                    </div>
                    <table class="table table-sm table-hover w-100 table-bordered" id="datatablexxx">
                        <thead class="bg-light">
                            <tr class="text-center">
                                @if(in_array(Auth::user()->role_id, array(1,3,4,5,6)) && ( in_array(Request::route()->getName(), array('DataSol', 'DataPros', 'CloseDeb', 'RejectDeb', 'MasterData'))))
                                    <th class="text-center" rowspan="2" id="checkallth">
                                        <input type="checkbox" class="form-check-input checkbox-all checkvalueall">
                                    </th>
                                @endif
                                <th rowspan="2" style="width: 1px;white-space:nowrap">No</th>
                                @if(Auth::user()->role_id != role('inputer'))
                                    <th class="text-center nowrap" rowspan="2">Nama Inputer</th>
                                    <th class="text-center nowrap" rowspan="2">NPP</th>
                                    <th class="text-center nowrap" rowspan="2">Cabang</th>
                                @else
                                    <th class="text-center nowrap" rowspan="2">Nama Deb</th>
                                @endif
                                    <th class="text-center nowrap" rowspan="2">Status</th>
                                @if(Auth::user()->role_id != role('inputer'))
                                    <th class="text-center nowrap" rowspan="2">Nama Deb</th>
                                @endif
                                    <th class="text-center nowrap" colspan="2">Lokasi Usaha</th>
                                    <th class="text-center nowrap" rowspan="2">Bidang Usaha</th>
                                    <th class="text-center nowrap" rowspan="2">Sektor</th>
                                    <th class="text-center nowrap" rowspan="2">Kategori</th>
                                    <th class="text-center nowrap" rowspan="2">Orientasi Ekspor</th>
                                    <th class="text-center nowrap" rowspan="2">Indikasi Kebutuhan</th>
                                    <th class="text-center nowrap" rowspan="2">Sumber</th>
                                    <th class="text-center nowrap" rowspan="2">Nominal Usulan</th>
                                    <th class="text-center nowrap" rowspan="2">Nominal Putusan</th>
                                    <th class="text-center nowrap" rowspan="2">Nominal Cair</th>
                                    <th class="text-center nowrap" rowspan="2">Opsi</th>
                            </tr>
                            <tr>
                                <th class="text-center nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alamat Detail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th class="text-center nowrap">KodePos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index=>$a)
                            <tr>
                                @if(in_array(Auth::user()->role_id, array(1,3,4,5,6)) && ( in_array(Request::route()->getName(), array('DataSol', 'DataPros', 'CloseDeb', 'RejectDeb', 'MasterData'))))
                                    <td class="text-center">
                                        @if(in_array(Auth::user()->role_id, array(1,4,5,6)) && ($a->status_debitur == 1 || $a->status_debitur < 1 || $a->status_debitur == 6))
                                            <input type="checkbox" value="{{ $a->id }}" class="form-check-input checkbox-one checkvalue">
                                        @elseif(Auth::user()->role_id == 3 && ($a->status_debitur == 2 || $a->status_debitur == 4))
                                            <input type="checkbox" value="{{ $a->id }}" class="form-check-input checkbox-one checkvalue">
                                        @endif
                                    </td>
                                @endif

                                <td class="text-center pointer redirectdetail_{{ $a->id }}">{{$index+1}}</td>
                                @if(Auth::user()->role_id != role('inputer'))
                                    <td class="pointer redirectdetail_{{ $a->id }} nowrap">{{ $a->nama_input }}</td>
                                    <td class="pointer redirectdetail_{{ $a->id }} nowrap">{{ $a->npp_input }}</td>
                                    <td class="pointer redirectdetail_{{ $a->id }} nowrap">{{ ($a->picinputer->attribute->cabang_id != null ? $a->picinputer->attribute->cabang->nama : '-') }}</td>
                                @else
                                    <td class="pointer redirectdetail_{{ $a->id }} nowrap">{{ $a->nama_debitur }}</td>
                                @endif

                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">
                                    <span class="badge bg-{{ $a->statusdebitur->color }} mb-1">
                                        <i class="bi {{ $a->statusdebitur->status_debitur == 5 ? 'bi-check2-all' : '' }}
                                            {{ $a->statusdebitur->status_debitur == 3 ? 'bi-clock-history' : '' }}
                                            {{ $a->statusdebitur->status_debitur == 4 ? 'bi-clock-history' : '' }}
                                            {{ $a->statusdebitur->status_debitur == 1 ? 'bi-clock-history' : '' && $a->statusdebitur->status_debitur == 2 ? 'bi-clock-history' : '' }}
                                            {{ $a->statusdebitur->narasi == 'Solicit Ditolak Verifikator' ? 'bi-x-circle' : '' && $a->statusdebitur->narasi == 'Solicit Ditolak Approval' ? 'bi-x-circle' : '' }} ">
                                        </i> {{ $a->statusdebitur->narasi }}
                                    </span><br>
                                    <span class="mb-0">
                                        @php
                                            $datas      = $a->toArray();

                                            $from       = date_create(date('Y-m-d', strtotime($datas[$a->statusdebitur->kolom_acuan_waktu])));
                                            $to         = date_create(date('Y-m-d'));
                                            $diff       = date_diff($to,$from);
                                            if($diff->format('%a') <= 34)
                                            {
                                                echo $diff->format('%a hari');
                                            }
                                            else
                                            {
                                                echo round($diff->format('%a')/30.417 , 0).' bulan';
                                            }
                                        @endphp
                                         lalu
                                    </span>

                                </td>

                                @if(Auth::user()->role_id != role('inputer'))
                                    <td class="pointer redirectdetail_{{ $a->id }} nowrap">{{ $a->nama_debitur }}</td>
                                @endif
                                <td class="pointer">
                                    <span class="redirectdetail_{{ $a->id }}">
                                        {{ $a->detail_alamat }}
                                    </span> <br>
                                    <a target="_blank" href="{{ route('openfile', ['path' => $a->dokumen_lokasi]) }}" class="btn btn-sm btn-primary w-100">Foto Lokasi</a>
                                </td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->kodepos }}</td>

                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->bidang_usaha }}</td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->sektor }}</td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->kategori }}</td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->orientasiekspor }}</td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->indikasi_kebutuhan_produk }}</td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->sumber }}</td>

                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->nominal_usulan }}</td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->nominal_keputusan }}</td>
                                <td class="pointer redirectdetail_{{ $a->id }} text-center nowrap">{{ $a->nominal_cair }}</td>

                                <td class="text-center" style="white-space: nowrap">
                                    @if(in_array(Auth::user()->role_id, array(1,4,5)) && $a->status_debitur == 1)
                                        <a href="{{ route('solicitedit', ['id' => $a->id]) }}" class="btn btn-sm btn-warning ml-2" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                    @endif
                                    @if(in_array(Auth::user()->role_id, array(1,4,5)) && ($a->status_debitur == 1 || $a->status_debitur < 1 || $a->status_debitur == 6))
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $a->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                    @endif
                                        <a target="_blank" href="{{ route('printdata', ['id' => $a->id]) }}" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Print"><i class="bi bi-filetype-pdf"></i></a>
                                </td>
                            </tr>
                            <script>
                                $(document).ready(function(){
                                   $('.redirectdetail_{{ $a->id }}').attr('onclick', "OpenURL('datadebdetail/{{ $a->id }}')")
                                   $('.redirectdetail_{{ $a->id }}').attr('title', "Klik untuk melihat detail data")
                                })
                            </script>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>

<form class="form-delete d-none" method="post" action="{{ route('solicitdelete') }}">
    @csrf
    <input type="hidden" name="routename" value="{{ Request::route()->getName() }}">
    <input type="hidden" name="id">
</form>

<form class="d-none" id="solicitdeleteall" method="post" action="{{ route('solicitdeleteall') }}">
    @csrf
    <input type="hidden" name="id" id="id_deleteall">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
</form>

<form class="d-none" id="solicitappall" method="post" action="{{ route('solicitappall') }}">
    @csrf
    <input type="hidden" name="id" id="id_appall">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
</form>

<form class="d-none" id="solicitappprosall" method="post" action="{{ route('prospectappall') }}">
    @csrf
    <input type="hidden" name="id" id="id_appprosall">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
</form>

<div class="modal fade" id="mdldenydataall" tabindex="-1" aria-labelledby="mdldenydataall" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Tolak Data</h5>
                <button type="button" class="btn btn-sm btn-primary" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="solicitdenyall" method="post" action="{{ route('solicitdenyall') }}">
                    @csrf
                    <input type="hidden" name="id" id="id_denyall">
                    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            Apakah anda yakin ingin menolak data ini?
                        </div>
                        <div class="col-12 mb-2">
                            <label style="font-weight:bold">Alasan Penolakan</label>
                            <textarea class="form-control" required name="alasantolak" placeholder="Alasan Penolakan" rows="5"></textarea>
                        </div>
                        <div class="col-md-12 mb-2 mt-2 text-center">
                            <button type="submit" class="btn btn-primary">Ya, Tolak Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlverifikasiall" tabindex="-1" aria-labelledby="mdlverifikasiall" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="ModalShowListLabel">Verifikasi Data</h5>
                <button type="button" class="btn btn-sm btn-primary" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="solicitverifall" action="{{ route('solicitverifall') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id_verifall">
                    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            Apakah anda yakin ingin memverifikasi data ini? <br>
                            Pastikan Semua Data Sudah benar!
                        </div>
                        <div class="col-4 mb-2">
                        </div>
                        <div class="col-8 mb-2">
                            <input required type="checkbox" name="pre_screen" value="1">
                            <label for="pre_screen" style="font-weight: bold"> Pre Screen</label><br>
                            <input required type="checkbox" name="ots_penyelia" value="1">
                            <label for="ots_penyelia" style="font-weight: bold"> OTS Penyelia</label><br>
                            <input required type="checkbox" name="ots_pemimpin" value="1">
                            <label for="ots_pemimpin" style="font-weight: bold"> OTS Pemimpin</label><br>
                        </div>
                        <div class="col-md-12 mb-2 mt-2 text-center">
                            <button type="submit" class="btn btn-primary">Ya, Verifikasi Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function(){
        fixed       = 1;
        let table   = '';
        if("{{Request::route()->getName() == 'DataSol'}}")
        {
            fixed = 1;
        }

        if("{{Request::route()->getName() == 'DataSol'}}")
        {
            var titleee = 'Data Solicit';
        }
        else if("{{Request::route()->getName() == 'DataPros'}}")
        {
            var titleee = 'Data Prospek';
        }
        else if("{{Request::route()->getName() == 'DataPipe'}}")
        {
            var titleee = 'Data Prospek';
        }
        else if("{{Request::route()->getName() == 'CloseDeb'}}")
        {
            var titleee = 'Data Close';
        }
        else if("{{Request::route()->getName() == 'RejectDeb'}}")
        {
            var titleee = 'Data Reject';
        }
        else
        {
            var titleee = 'Data Debitur' ;
        }

        setTimeout(function() {
            table = new DataTable('#datatablexxx', {
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                fixedColumns:
                {
                    leftColumns: 0,
                    rightColumns:fixed
                },
                bFilter: true,
                bInfo: true,
                dom: 'Blfrtip',
                responsive: false,
                buttons: [
                    {
                        extend: 'excel',
                        className: 'exportbtn',
                        title: titleee
                    }
                ]
            });
        }, 1000);

        $('.dataTables_sizing .checkvalueall').remove()
        $('.checkvalueall').on('change', function(){
            var arr = [];
            $('input.checkvalue:checkbox:checked').each(function () {
                arr.push($(this).val());
            });
            if(arr.length === 0 )
            {
                $('#btnactionall').css('display','none')
            }
            else
            {
                $('#btnactionall').css('display','')
            }
            $('.dataTables_sizing .checkvalueall').remove()
            table.columns.adjust().draw();
        })

        $('.checkvalue').on('change', function(){
            var arr = [];
            $('input.checkvalue:checkbox:checked').each(function () {
                arr.push($(this).val());
            });
            if(arr.length === 0 )
            {
                $('#btnactionall').css('display','none')
            }
            else
            {
                $('#btnactionall').css('display','')
            }
            $('.dataTables_sizing .checkvalueall').remove()
            table.columns.adjust().draw();
        })
    })
    function action_all(type)
    {
        var arr = [];
        $('input.checkvalue:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        if(arr.length === 0 )
        {
            Spandiv.LoadResources(Spandiv.Resources.sweetalert2, function() {
                Swal.fire({
                    title: 'Perhatian',
                    text:  'Belum terdapat data yang dipilih',
                    icon: "warning",
                    allowOutsideClick: false,
                })
            })
        }
        else
        {
            if(type == 'deny')
            {
                $('#id_'+type+'all').val(arr.join(","))
                $('#mdldenydataall').modal('show')
            }
            else if(type == 'verif')
            {
                $('#id_'+type+'all').val(arr.join(","))
                $('#mdlverifikasiall').modal('show')
            }
            else
            {
                Spandiv.LoadResources(Spandiv.Resources.sweetalert2, function() {
                    var nrs     = (type == 'delete' ? ' menghapus ' : ' mengapprove ');
                    var btnrs   = (type == 'delete' ? ' Hapus ' : ' Approve ');
                    var htmlxx    =  `  <div class="row">
                                            <div class="col-md-12 text-center" >
                                                Apakah anda yakin ingin `+nrs+` `+arr.length+` data terpilih?
                                            </div>
                                        </div>`;

                    Swal.fire({
                        title: 'Perhatian',
                        icon: "warning",
                        confirmButtonText: "Ya"+btnrs,
                        confirmButtonColor: "#3085d6",
                        showCancelButton: true,
                        cancelButtonText:'Batal',
                        html: htmlxx,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#id_'+type+'all').val(arr.join(","))
                            $("#solicit"+type+"all").submit();
                        }
                    });
                });
            }
        }
    }
</script>


@endsection

@section('js')
    <script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script> --}}
    {{-- <script type="text/javascript" src="{{asset('/dttables')}}/Bootstrap-4-4.6.0/js/bootstrap.min.js"></script> --}}
    <script type="text/javascript" src="{{asset('/dttables')}}/JSZip-2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/DataTables-1.13.1/js/jquery.dataTables.min.js"></script>
    {{-- <script type="text/javascript" src="{{asset('/dttables')}}/DataTables-1.13.1/js/dataTables.bootstrap4.min.js"></script> --}}
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/FixedColumns-4.2.1/js/dataTables.fixedColumns.min.js"></script>
    {{-- <script type="text/javascript" src="{{asset('/dttables')}}/Select-1.5.0/js/dataTables.select.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js" integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script type="text/javascript" src="{{asset('/datepicker')}}/datepicker.min.js"></script>

    <script type="text/javascript">
        Spandiv.DataTable("#datatable");
        Spandiv.ButtonDelete(".btn-delete", ".form-delete");

        function OpenURL(url)
        {
            var NewUrl = "<?= URL::to('"+url+"') ?>"
                window.location.href = NewUrl
        }
    </script>

@endsection
