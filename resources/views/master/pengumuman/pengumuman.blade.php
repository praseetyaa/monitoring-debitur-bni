@extends('faturhelper::layouts/admin/main')

@section('title', 'Data Pengumuman')

@section('content')
<style>
    .pointer
    {
        cursor: pointer;
    }
</style>
<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Data Pengumuman</h1>
    <div class="btn-group">
        <a href="{{ route('pengumumancreate') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Pengumuman</a>
    </div>
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
                    <div class="col-md-9">
                        <label class="mb-2" style="font-weight: bold">Status</label>
                        <select required id="status" class="form-select">
                            <option value="" {{$status == '' ? 'selected' : ''}}>Semua Status Data</option>
                            <option value="Act" {{$status == 'Act' ? 'selected' : ''}}>Not Expired</option>
                            <option value="Exp" {{$status == 'Exp' ? 'selected' : ''}}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-end">
                        <label class="mb-2" style="font-weight: bold">&nbsp;</label><br>
                        <a onclick="setfilter()" class="btn btn-sm btn-secondary mr-2">Filter Data</a>
                        <a onclick="resetfilter()" class="btn btn-sm btn-danger">Reset Filter</a>
                    </div>
                </div>
                <script>
                    function resetfilter()
                    {
                        var NewUrl = "<?= URL::to('master/pengumuman/') ?>"
                        window.location.href = NewUrl
                    }
                    function setfilter()
                    {
                        var status = $('#status').val() != '' ?  $('#status').val() : null
                        var NewUrl = "<?= URL::to('master/pengumuman/"+status+"') ?>"
                        window.location.href = NewUrl
                    }
                </script>

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered w-100" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 1px;white-space:nowrap">No</th>
                                <th style="white-space: nowrap">Judul</th>
                                <th style="white-space: nowrap">Expired</th>
                                <th style="white-space: nowrap">Pembuat</th>
                                <th style="white-space: nowrap">Tanggal Dibuat</th>
                                <th style="white-space: nowrap">Pengumuman</th>
                                <th style="white-space: nowrap">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index=>$a)
                            <tr>
                                <td class="text-center pointer">{{$index+1}}</td>
                                <td class="text-center">{{ $a->judul }}</td>
                                <td class="text-center">
                                    @if (strtotime($a->expired) < strtotime(date('Y-m-d')))
                                      <span class="badge bg-danger">{{ date('d M Y', strtotime($a->expired)) }}</span>
                                    @else
                                      <span class="badge bg-info">{{ date('d M Y', strtotime($a->expired)) }}</span>
                                    @endif
                                </td>
                                <td>{{ $a->nama_pembuat }}</td>
                                <td class="text-center">{{ date('d M Y', strtotime($a->tanggal_pebuatan)) }}</td>
                                <td class="text-center">
                                    @php
                                        $datas      = base64_encode($a->isi);
                                        $juduls     = base64_encode($a->judul);
                                    @endphp
                                    <a onclick="show_pengumuman('{{ $datas }}', '{{$juduls}}')" class="btn btn-sm btn-primary">Lihat Pengumuman</a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pengumumanedit', ['id' => $a->id]) }}" class="btn btn-sm btn-warning ml-2" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $a->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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


<form class="form-delete d-none" method="post" action="{{ route('pengumumandelete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    Spandiv.DataTable("#datatable");
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
</script>

@endsection
