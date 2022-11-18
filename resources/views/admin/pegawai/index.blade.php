@extends('faturhelper::layouts/admin/main')

@section('title', 'Manajemen Pegawai')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Manajemen Pegawai</h1>
    <div class="btn-group">
        <a href="{{ route('admin.pegawai.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Pegawai</a>
        <a href="#" class="btn btn-sm btn-danger btn-delete-bulk"><i class="bi-trash me-1"></i> Hapus Terpilih</a>
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
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th width="30"><input type="checkbox" class="form-check-input checkbox-all"></th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>NPP</th>
                                <th>Cabang</th>
                                <th width="60">Monitoring</th>
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pegawai as $p)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one" data-id="{{ $p->id }}"></td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->username }}</td>
                                <td>{{ $p->attribute->npp }}</td>
                                <td>{{ $p->attribute->cabang->nama }}</td>
                                <td>{{ number_format($p->monitoring->count()) }}</td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.monitoring.amount', ['id' => $p->id]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail Monitoring"><i class="bi-eye"></i></a>
                                        <a href="{{ route('admin.pegawai.edit', ['id' => $p->id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $p->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.pegawai.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

<form class="form-delete-bulk d-none" method="post" action="{{ route('admin.pegawai.delete-bulk') }}">
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
</script>

@endsection