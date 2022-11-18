@extends('faturhelper::layouts/admin/main')

@section('title', 'Kelola Vendor')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola Vendor</h1>
    <div class="btn-group">
        <a href="{{ route('admin.vendor.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Vendor</a>
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
                                @foreach($tipe as $t)
                                <th width="50" class="small">{{ $t->nama }}</th>
                                @endforeach
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendor as $v)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td>{{ $v->nama }}</td>
                                @foreach($tipe as $t)
                                <td align="center">
                                    <span class="d-none">{{ $t->id }}</span>
                                    <input class="form-check-input checkbox-tipe-vendor" type="checkbox" data-tipe="{{ $t->id }}" data-vendor="{{ $v->id }}" {{ in_array($t->id, $v->tipe()->pluck('tipe_id')->toArray()) ? 'checked' : '' }}>
                                </td>
                                @endforeach
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.vendor.edit', ['id' => $v->id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $v->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.vendor.delete') }}">
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
    $(document).on("click", "#datatable .form-check-input.checkbox-tipe-vendor", function(e) {
        e.preventDefault();
        if(typeof Pace !== "undefined") Pace.restart();
        var vendor = $(this).data("vendor");
        var tipe = $(this).data("tipe");
        $.ajax({
            type: "post",
            url: "{{ route('admin.vendor.change') }}",
            data: {_token: "{{ csrf_token() }}", vendor: vendor, tipe: tipe},
            success: function(response) {
                if(response == "Berhasil mengganti tipe vendor.") {
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