@extends('faturhelper::layouts/admin/main')

@section('title', 'Tambah Jenis Fasilitas')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Tambah Jenis Fasilitas</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('fasilitasstore') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="route" value="{{@$_GET['role']}}">

                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jenis Fasilitas<span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input required type="text" name="jenis_fasilitas" class="form-control form-control-sm" autofocus>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('fasilitas') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection
