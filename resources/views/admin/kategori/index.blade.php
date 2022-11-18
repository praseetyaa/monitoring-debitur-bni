@extends('faturhelper::layouts/admin/main')

@section('title', 'Kelola Kategori')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola Kategori</h1>
    <div class="btn-group">
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Kategori</a>
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
                                <th>Kategori</th>
                                <th width="100">Jenis</th>
                                @foreach($tipe as $t)
                                <th width="50" class="small">{{ $t->nama }}</th>
                                @endforeach
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori as $k)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td>{{ $k->awal }} <strong>(Ya/Tidak)</strong> {{ $k->akhir }}</td>
                                <td>{{ $k->jenis->nama }}</td>
                                @foreach($tipe as $t)
                                <td align="center">
                                    <span class="d-none">{{ $t->id }}</span>
                                    <input class="form-check-input checkbox-tipe-kategori" type="checkbox" data-tipe="{{ $t->id }}" data-kategori="{{ $k->id }}" {{ in_array($t->id, $k->tipe()->pluck('tipe_id')->toArray()) ? 'checked' : '' }}>
                                </td>
                                @endforeach
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.kategori.edit', ['id' => $k->id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $k->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.kategori.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 d-none">
    <div class="toast align-items-center text-white bg-success border-0" id="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable", {
        pageLength: -1
    });

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");

    // Change Status
    $(document).on("click", "#datatable .form-check-input.checkbox-tipe-kategori", function(e) {
        e.preventDefault();
        if(typeof Pace !== "undefined") Pace.restart();
        var kategori = $(this).data("kategori");
        var tipe = $(this).data("tipe");
        $.ajax({
            type: "post",
            url: "{{ route('admin.kategori.change') }}",
            data: {_token: "{{ csrf_token() }}", kategori: kategori, tipe: tipe},
            success: function(response) {
                if(response == "Berhasil mengganti tipe kategori.") {
                    $("#toast").hasClass("bg-danger") ? $("#toast").removeClass("bg-danger") : '';
                    !$("#toast").hasClass("bg-success") ? $("#toast").addClass("bg-success") : '';
                    e.target.checked = !e.target.checked;
                }
                else {
                    $("#toast").hasClass("bg-success") ? $("#toast").removeClass("bg-success") : '';
                    !$("#toast").hasClass("bg-danger") ? $("#toast").addClass("bg-danger") : '';
                }
                Spandiv.Toast("#toast", response);
            }
        });
    });
</script>

@endsection

@section('css')

<style type="text/css">
    .table tr td:not(:first-child) .form-check-input {height: 1.25rem; width: 1.25rem;}
</style>

@endsection