@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit Kategori')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Kategori</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.kategori.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $kategori->id }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="awal" class="form-control form-control-sm {{ $errors->has('awal') ? 'border-danger' : '' }}" value="{{ $kategori->awal }}">
                                <span class="input-group-text">Ya/Tidak</span>
                                <input type="text" name="akhir" class="form-control form-control-sm {{ $errors->has('akhir') ? 'border-danger' : '' }}" value="{{ $kategori->akhir }}">
                            </div>
                            @if($errors->has('awal'))
                            <div class="small text-danger">{{ $errors->first('awal') }}</div>
                            @endif
                            @if($errors->has('akhir'))
                            <div class="small text-danger">{{ $errors->first('akhir') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Opsi Jika Ya <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="opsi_ya" class="form-control form-control-sm {{ $errors->has('opsi_ya') ? 'border-danger' : '' }}" value="{{ $kategori->opsi_ya != '' ? $kategori->opsi_ya : 'Ya' }}">
                            @if($errors->has('opsi_ya'))
                            <div class="small text-danger">{{ $errors->first('opsi_ya') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Opsi Jika Tidak <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="opsi_tidak" class="form-control form-control-sm {{ $errors->has('opsi_tidak') ? 'border-danger' : '' }}" value="{{ $kategori->opsi_tidak != '' ? $kategori->opsi_tidak : 'Tidak' }}">
                            @if($errors->has('opsi_tidak'))
                            <div class="small text-danger">{{ $errors->first('opsi_tidak') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Kebalikan? <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_reverse" id="is_reverse-1" value="1" {{ $kategori->is_reverse == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_reverse-1">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_reverse" id="is_reverse-0" value="0" {{ $kategori->is_reverse == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_reverse-0">Tidak</label>
                            </div>
                            @if($errors->has('is_reverse'))
                            <div class="small text-danger">{{ $errors->first('is_reverse') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jenis <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            @foreach($jenis as $j)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis" id="jenis-{{ $j->id }}" value="{{ $j->id }}" {{ $kategori->jenis_id == $j->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="jenis-{{ $j->id }}">{{ $j->nama }}</label>
                            </div>
                            @endforeach
                            @if($errors->has('jenis'))
                            <div class="small text-danger">{{ $errors->first('jenis') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection