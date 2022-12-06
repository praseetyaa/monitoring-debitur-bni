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
</style>
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
                            <input required type="date" value="{{$startd}}" class="form-control" id="startd">
                        </div>
                        <div class="col-md-3">
                            <label class="mb-2" style="font-weight: bold">Tanggal Akhir</label>
                            <input required type="date" value="{{$endd}}" class="form-control" id="endd">
                        </div>
                        <div class="col-md-3">
                            <label class="mb-2" style="font-weight: bold">Status</label>
                            <select required id="status" class="form-select">
                                <option value="" {{$status == '' ? 'selected' : ''}}>Semua Status Data</option>
                                @foreach($StatusDebitur as $c)
                                    <option value="{{ $c->status_debitur }}" {{$status == $c->status_debitur ? 'selected' : ''}}>{{ $c->narasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-2" style="font-weight: bold">Cabang</label>
                            <select required id="cabang" class="form-select">
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
                            if("{{Auth::user()->role_id == role('inputer')}}")
                            {
                                $('#cabang').attr('disabled', true)
                            }

                            if("{{Request::route()->getName() != 'DataSol'}}")
                            {
                                $('#status').attr('disabled', true)
                            }
                        })
                        function resetfilter()
                        {
                            routename = "{{Request::route()->getName()}}"
                            console.log(routename);
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
                    @if(in_array(Auth::user()->role_id, array(3,6)))
                        <div class="row mb-2" id="btnactionall" style="display: none">
                            <div class="col-md-12 text-center">
                                @if(in_array(Auth::user()->role_id, array(6)))
                                    <a onclick="action_all('verif')" class="btn btn-sm btn-primary">Verif Data Terpilih</a>
                                @elseif(in_array(Auth::user()->role_id, array(3)) && Request::route()->getName() == 'DataSol')
                                    <a onclick="action_all('app')" class="btn btn-sm btn-primary">Approve Data Terpilih</a>
                                @endif
                                <a onclick="action_all('deny')" class="btn btn-sm btn-warning">Tolak Data Terpilih</a>
                                <a onclick="action_all('delete')" class="btn btn-sm btn-danger mr-2">Hapus Data Terpilih</a>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered w-100" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                @if(in_array(Auth::user()->role_id, array(3,6)))
                                    <th rowspan="2"><input type="checkbox" class="form-check-input checkbox-all checkvalueall"></th>
                                @endif
                                <th rowspan="2" style="width: 1px;white-space:nowrap">No</th>
                                @if(Auth::user()->role_id != role('inputer'))
                                    <th rowspan="2" style="white-space: nowrap">Nama Inputer</th>
                                    <th rowspan="2" style="white-space: nowrap">NPP</th>
                                    <th rowspan="2" style="white-space: nowrap">Cabang</th>
                                @else
                                    <th rowspan="2" style="white-space: nowrap">Nama Deb</th>
                                @endif
                                    <th rowspan="2" style="white-space: nowrap">Waktu</th>
                                @if(Auth::user()->role_id != role('inputer'))
                                    <th rowspan="2" style="white-space: nowrap">Nama Deb</th>
                                @endif
                                    <th colspan="2">Lokasi Usaha</th>
                                    <th rowspan="2">Status</th>
                                @if(Auth::user()->role_id == 4)
                                    <th rowspan="2">Opsi</th>
                                @endif
                            </tr>
                            <tr>
                                <th style="width: 30%!important">Alamat Detail</th>
                                <th>KodePos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index=>$a)
                            <tr>
                                @if(in_array(Auth::user()->role_id, array(3,6)))
                                    <td class="text-center">
                                        @if(Auth::user()->role_id == 6 && $a->status_debitur == 1)
                                            <input type="checkbox" value="{{ $a->id }}" class="form-check-input checkbox-one checkvalue">
                                        @elseif(Auth::user()->role_id == 3 && ($a->status_debitur == 2 || $a->status_debitur == 3))
                                            <input type="checkbox" value="{{ $a->id }}" class="form-check-input checkbox-one checkvalue">
                                        @endif
                                    </td>
                                @endif
                                <td class="text-center pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{$index+1}}</td>
                                @if(Auth::user()->role_id != role('inputer'))
                                    <td class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{ $a->nama_input }}</td>
                                    <td class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{ $a->npp_input }}</td>
                                    <td class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{ ($a->picinputer->attribute->cabang_id != null ? $a->picinputer->attribute->cabang->nama : '-') }}</td>
                                @else
                                    <td class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{ $a->nama_debitur }}</td>
                                @endif
                                <td class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{date('d M Y H:i:s', strtotime($a->created_at))}}</td>
                                @if(Auth::user()->role_id != role('inputer'))
                                    <td class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{ $a->nama_debitur }}</td>
                                @endif
                                <td>
                                    <span  class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">
                                        {{ $a->detail_alamat }}
                                    </span> <br>
                                    <a target="_blank" href="{{ route('openfile', ['path' => $a->dokumen_lokasi]) }}" class="btn btn-sm btn-primary w-100">Foto Lokasi</a>
                                </td>
                                <td class="pointer" onclick="OpenURL('datadebdetail/{{ $a->id }}')">{{ $a->kodepos }}</td>
                                <td class="pointer text-center" onclick="OpenURL('datadebdetail/{{ $a->id }}')">
                                    <p class="badge bg-{{ $a->statusdebitur->color }} mb-1">
                                        <i class="bi {{ $a->statusdebitur->status_debitur == 4 ? 'bi-check2-all' : '' }}
                                            {{ $a->statusdebitur->status_debitur == 3 ? 'bi-clock-history' : '' }}
                                            {{ $a->statusdebitur->status_debitur == 1 ? 'bi-clock-history' : '' && $a->statusdebitur->status_debitur == 2 ? 'bi-clock-history' : '' }}
                                            {{ $a->statusdebitur->narasi == 'Solicit Ditolak Verifikator' ? 'bi-x-circle' : '' && $a->statusdebitur->narasi == 'Solicit Ditolak Approval' ? 'bi-x-circle' : '' }} ">
                                        </i> {{ $a->statusdebitur->narasi }}
                                    </p>
                                    <p class="mb-0">
                                        @php
                                            $datas      = $a->toArray();

                                            $from       = date_create(date('Y-m-d', strtotime($datas[$a->statusdebitur->kolom_acuan_waktu])));
                                            $to         = date_create(date('Y-m-d'));
                                            $diff       = date_diff($to,$from);
                                            if($diff->format('%a') <= 34)
                                            {
                                                echo $diff->format('%a Hari');
                                            }
                                            else
                                            {
                                                echo round($diff->format('%a')/30.417 , 0).' Bulan';
                                            }
                                        @endphp
                                         Yang Lalu
                                    </p>

                                </td>
                                @if(Auth::user()->role_id == 4)
                                    <td class="text-center" style="white-space: nowrap">
                                        @if(in_array(Auth::user()->role_id, array(1,4,5)) && $a->status_debitur == 1 && Request::route()->getName() == 'DataSol')
                                            <a href="{{ route('solicitedit', ['id' => $a->id]) }}" class="btn btn-sm btn-warning ml-2" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $a->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
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
    <input type="hidden" name="id">
</form>

<form class="d-none" id="solicitdeleteall" method="post" action="{{ route('solicitdeleteall') }}">
    @csrf
    <input type="hidden" name="id" id="id_deleteall">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
</form>
<form class="d-none" id="solicitdenyall" method="post" action="{{ route('solicitdenyall') }}">
    @csrf
    <input type="hidden" name="id" id="id_denyall">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
</form>
<form class="d-none" id="solicitverifall" method="post" action="{{ route('solicitverifall') }}">
    @csrf
    <input type="hidden" name="id" id="id_verifall">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
</form>
<form class="d-none" id="solicitappall" method="post" action="{{ route('solicitappall') }}">
    @csrf
    <input type="hidden" name="id" id="id_appall">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
</form>
<script>
    $(document).ready(function(){
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
            Spandiv.LoadResources(Spandiv.Resources.sweetalert2, function() {
                var nrs     = (type == 'delete' ? ' menghapus ' : type == 'verif' ? ' memverifikasi ' : type == 'deny' ? ' menolak ' : ' mengapprove ');
                var btnrs   = (type == 'delete' ? ' Hapus ' : type == 'verif' ? ' Verifikasi ' : type == 'deny' ? ' Tolak ' : ' Approve ');
                var htmlxx    =  `  <div class="row">
                                        <div class="col-md-12 text-center" >
                                            Apakah anda yakin ingin `+nrs+` `+arr.length+` data terpilih?
                                        </div>
                                    </div>`;
                if(type == 'verif')
                {
                    htmlxx = `  <div class="row">
                                    <div class="col-md-12 text-center" >
                                        Apakah anda yakin ingin `+nrs+` `+arr.length+` data terpilih?
                                    </div>
                                    <div class="col-md-12 text-left">
                                        <input disabled type="checkbox" checked name="pre_screen" value="1">
                                        <label for="pre_screen" style="font-weight: bold"> Pre Screen</label><br>
                                        <input disabled type="checkbox" checked name="ots_penyelia" value="1">
                                        <label for="ots_penyelia" style="font-weight: bold"> OTS Penyelia</label><br>
                                        <input disabled type="checkbox" checked name="ots_pemimpin" value="1">
                                        <label for="ots_pemimpin" style="font-weight: bold"> OTS Pemimpin</label><br>
                                    </div>
                                </div>`;
                }

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
</script>


@endsection

@section('js')

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
