@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit Sektor')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Edit Sektor</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('sektorupdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">

                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama Sektor<span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="nama_sektor" class="form-control form-control-sm {{ $errors->has('nama_sektor') ? 'border-danger' : '' }}" value="{{ $data->nama_sektor }}" autofocus>
                            @if($errors->has('nama_sektor'))
                            <div class="small text-danger">{{ $errors->first('nama_sektor') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('sektor') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection
