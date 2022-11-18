@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit ATM')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit ATM</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.atm.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $atm->id }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="nama" class="form-control form-control-sm {{ $errors->has('nama') ? 'border-danger' : '' }}" value="{{ $atm->nama }}" autofocus>
                            @if($errors->has('nama'))
                            <div class="small text-danger">{{ $errors->first('nama') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">ID</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="id_atm" class="form-control form-control-sm {{ $errors->has('id_atm') ? 'border-danger' : '' }}" value="{{ $atm->id_atm }}">
                            @if($errors->has('id_atm'))
                            <div class="small text-danger">{{ $errors->first('id_atm') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Cabang <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="cabang" class="form-select form-select-sm {{ $errors->has('cabang') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($cabang as $c)
                                <option value="{{ $c->id }}" {{ $atm->cabang_id == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cabang'))
                            <div class="small text-danger">{{ $errors->first('cabang') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Vendor <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="vendor" class="form-select form-select-sm {{ $errors->has('vendor') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($vendor as $v)
                                <option value="{{ $v->id }}" {{ $atm->vendor_id == $v->id ? 'selected' : '' }}>{{ $v->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('vendor'))
                            <div class="small text-danger">{{ $errors->first('vendor') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.atm.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection