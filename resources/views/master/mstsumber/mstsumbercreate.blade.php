@extends('faturhelper::layouts/admin/main')

@section('title', 'Tambah Sumber')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Tambah Sumber</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('sumberstore') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="route" value="{{@$_GET['role']}}">

                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama Sumber<span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="nama_sumber" class="form-control form-control-sm {{ $errors->has('nama_sumber') ? 'border-danger' : '' }}" value="{{ old('nama_sumber') }}" autofocus>
                            @if($errors->has('nama_sumber'))
                            <div class="small text-danger">{{ $errors->first('nama_sumber') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('sumber') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection
