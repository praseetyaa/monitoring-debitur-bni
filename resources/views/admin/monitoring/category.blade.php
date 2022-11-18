@extends('faturhelper::layouts/admin/main')

@section('title', 'Master Wilayah: '.$kategori->awal.' '.($kategori->is_reverse == 0 ? 'Tidak' : '').' '.$kategori->akhir)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Master Wilayah</h1>
    <div class="btn-group">
        <a href="{{ route('admin.monitoring.category', array_merge(Request::query(), ['id' => $kategori->id, 'print' => 'excel'])) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi-file-excel me-1"></i> Ekspor Excel</a>
        <a href="{{ route('admin.monitoring.category', array_merge(Request::query(), ['id' => $kategori->id, 'print' => 'pdf'])) }}" target="_blank" class="btn btn-sm btn-warning"><i class="bi-file-pdf me-1"></i> Ekspor PDF</a>
    </div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-body">
                <div class="alert alert-success fade show" role="alert">
                    <div class="alert-message">Rekapitulasi data <strong>{{ $kategori->awal }} {{ $kategori->is_reverse == 0 ? 'Tidak' : '' }} {{ $kategori->akhir }}</strong> dari tanggal <strong>{{ date('d/m/Y', strtotime($t1)) }}</strong> sampai <strong>{{ date('d/m/Y', strtotime($t2)) }}</strong>.</div>
                </div>
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
                            @foreach($monitoring_detail as $md)
                            <?php $m = $md->monitoring; ?>
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
                                        <!-- <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $m->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a> -->
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