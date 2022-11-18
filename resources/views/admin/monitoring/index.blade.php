@extends('faturhelper::layouts/admin/main')

@section('title', 'Master Wilayah')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Master Wilayah</h1>
    <div class="btn-group">
        <a href="{{ route('admin.monitoring.index', array_merge(Request::query(), ['print' => 'excel'])) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi-file-excel me-1"></i> Ekspor Excel</a>
        <a href="{{ route('admin.monitoring.index', array_merge(Request::query(), ['print' => 'pdf'])) }}" target="_blank" class="btn btn-sm btn-warning"><i class="bi-file-pdf me-1"></i> Ekspor PDF</a>
        <a href="#" class="btn btn-sm btn-danger btn-delete-bulk"><i class="bi-trash me-1"></i> Hapus Terpilih</a>
    </div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-header d-sm-flex justify-content-center align-items-center">
                <form id="form-filter" class="d-lg-flex" method="get" action="">
                    @if(Auth::user()->role->is_global == 1)
                    <div class="mb-lg-0 mb-2">
                        <select name="cabang" class="form-select form-select-sm" data-bs-toggle="tooltip" title="Pilih Cabang">
                            <option value="0">Semua Cabang</option>
                            @foreach($cabang as $c)
                            <option value="{{ $c->id }}" {{ Request::query('cabang') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @if(Auth::user()->role_id == role('admin-vendor'))
                    <div class="mb-lg-0 mb-2">
                        <select name="vendor" class="form-select form-select-sm" data-bs-toggle="tooltip" title="Pilih Vendor">
                            <option value="0">Semua Vendor</option>
                            @foreach($vendor as $c)
                            <option value="{{ $c->id }}" {{ Request::query('vendor') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="ms-lg-2 ms-0 mb-lg-0 mb-2">
                        <input type="text" id="t1" name="t1" class="form-control form-control-sm input-tanggal" value="{{ date('d/m/Y', strtotime($t1)) }}" autocomplete="off" data-bs-toggle="tooltip" title="Dari Tanggal">
                    </div>
                    <div class="ms-lg-2 ms-0 mb-lg-0 mb-2">
                        <input type="text" id="t2" name="t2" class="form-control form-control-sm input-tanggal" value="{{ date('d/m/Y', strtotime($t2)) }}" autocomplete="off" data-bs-toggle="tooltip" title="Sampai Tanggal">
                    </div>
                    <div class="ms-lg-2 ms-0">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi-filter-square me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
            <hr class="my-0">
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th width="30"><input type="checkbox" class="form-check-input checkbox-all"></th>
                                <th width="100">Waktu</th>
                                <th>Petugas</th>
                                <th>NPP</th>
                                <th>Vendor</th>
                                <th>ATM</th>
                                <th>Cabang</th>
                                <th width="200">Lokasi</th>
                                <th width="30">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monitoring as $m)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one" data-id="{{ $m->id }}"></td>
                                <td>
                                    <span class="d-none">{{ $m->created_at }}</span>
                                    {{ date('d/m/Y', strtotime($m->created_at)) }}
                                    <div class="small">{{ date('H:i', strtotime($m->created_at)) }} WIB</div>
                                </td>
                                <td>{{ $m->user->name }}</td>
                                <td>{{ $m->user->role_id == role('pegawai') ? $m->user->attribute->npp : '-' }}</td>
                                <td>
                                    @if($m->vendor)
                                        {{ $m->vendor->nama }}
                                        <div class="small">{{ $m->tipe ? $m->tipe->nama : '' }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $m->atm->nama }}
                                    <div class="small">ID: {{ $m->atm->id_atm }}</div>
                                </td>
                                <td>{{ $m->cabang->nama }}</td>
                                <td>{{ $m->lokasi }}</td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.monitoring.detail', ['id' => $m->id]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                                        <a href="{{ route('admin.monitoring.edit', ['id' => $m->id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $m->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                    </div>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.monitoring.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

<form class="form-delete-bulk d-none" method="post" action="{{ route('admin.monitoring.delete-bulk') }}">
    @csrf
    <input type="hidden" name="ids">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable");

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");

    // Button Delete
    Spandiv.ButtonDeleteBulk(".btn-delete-bulk", ".form-delete-bulk");

    // Datepicker
    Spandiv.DatePicker("input[name=t1], input[name=t2]");
</script>

@endsection

@section('css')

<style>
    #datatable tr td {vertical-align: top!important;}
</style>

@endsection