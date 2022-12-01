@extends('faturhelper::layouts/admin/main')

@section('title', 'Data Solicit')

@section('content')
<style>
    .pointer
    {
        cursor: pointer;
    }
</style>
<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Data Solicit</h1>
    @if(in_array(Auth::user()->role_id, array(1,4,5)))
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
                    <div class="col-md-3 text-center">
                        <label class="mb-2" style="font-weight: bold">&nbsp;</label><br>
                        <a onclick="setfilter()" class="btn btn-sm btn-secondary mr-2">Filter Data</a>
                        <a onclick="resetfilter()" class="btn btn-sm btn-danger">Reset Filter</a>
                    </div>
                </div>
                <script>
                    function resetfilter()
                    {
                        var NewUrl = "<?= URL::to('daftarsolicit/') ?>"
                        window.location.href = NewUrl
                    }
                    function setfilter()
                    {
                        var stard = $('#startd').val() != '' ?  $('#startd').val() : null
                        var endd = $('#endd').val() != '' ?  $('#endd').val() : null
                        var status = $('#status').val() != '' ?  $('#status').val() : null
                        var NewUrl = "<?= URL::to('daftarsolicit/"+stard+"/"+endd+"/"+status+"') ?>"
                        window.location.href = NewUrl
                    }
                </script>

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered w-100" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2" style="width: 1px;white-space:nowrap">No</th>
                                <th rowspan="2" style="white-space: nowrap">Nama Deb</th>
                                <th colspan="2">Lokasi Usaha</th>
                                <th rowspan="2">Sektor</th>
                                <th rowspan="2">Sumber</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2">Opsi</th>
                            </tr>
                            <tr>
                                <th style="width: 40%!important">Alamat Detail</th>
                                <th>KodePos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index=>$a)
                            <tr>
                                <td class="text-center pointer" onclick="OpenURL('solicit/solicitdetail/{{ $a->id }}')">{{$index+1}}</td>
                                <td class="pointer" onclick="OpenURL('solicit/solicitdetail/{{ $a->id }}')">{{ $a->nama_debitur }}</td>
                                <td>
                                    <span  class="pointer" onclick="OpenURL('solicit/solicitdetail/{{ $a->id }}')">
                                        {{ $a->detail_alamat }}
                                    </span> <br>
                                    <a target="_blank" href="{{ route('openfile', ['path' => $a->dokumen_lokasi]) }}" class="btn btn-sm btn-primary w-100">Foto Lokasi</a>
                                </td>
                                <td class="pointer" onclick="OpenURL('solicit/solicitdetail/{{ $a->id }}')">{{ $a->kodepos }}</td>
                                <td class="pointer" onclick="OpenURL('solicit/solicitdetail/{{ $a->id }}')">{{ $a->sektor }}</td>
                                <td class="pointer" onclick="OpenURL('solicit/solicitdetail/{{ $a->id }}')">{{ $a->sumber }} {{($a->dataleads != '' ? '('.$a->dataleads.')' : '')}}</td>
                                <td class="pointer" onclick="OpenURL('solicit/solicitdetail/{{ $a->id }}')"><span class="badge bg-{{ $a->statusdebitur->color }}">{{ $a->statusdebitur->narasi }}</span>  </td>
                                <td class="text-center" style="white-space: nowrap">
                                    @if(in_array(Auth::user()->role_id, array(1,4,5)) && $a->status_debitur == 1)
                                        <a href="{{ route('solicitedit', ['id' => $a->id]) }}" class="btn btn-sm btn-warning ml-2" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $a->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                    @endif
                                </td>
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
