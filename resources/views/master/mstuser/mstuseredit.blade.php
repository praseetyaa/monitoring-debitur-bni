@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit Pengguna')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Edit Pengguna {{@$role}}</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('picupdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $pengguna->id }}">
                    <input type="hidden" name="route" value="{{@$role}}">

                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="nama" class="form-control form-control-sm {{ $errors->has('nama') ? 'border-danger' : '' }}" value="{{ $pengguna->name }}" autofocus>
                            @if($errors->has('nama'))
                            <div class="small text-danger">{{ $errors->first('nama') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">NPP <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="npp" class="form-control form-control-sm {{ $errors->has('npp') ? 'border-danger' : '' }}" value="{{ $pengguna->attribute->npp }}">
                            @if($errors->has('npp'))
                            <div class="small text-danger">{{ $errors->first('npp') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. Telephone <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="phone_number" class="form-control form-control-sm {{ $errors->has('phone_number') ? 'border-danger' : '' }}" value="{{ $pengguna->attribute->phone_number }}">
                            @if($errors->has('phone_number'))
                            <div class="small text-danger">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Cabang <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="cabang" class="form-select form-select-sm {{ $errors->has('cabang') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($cabang as $c)
                                <option value="{{ $c->id }}" {{ $pengguna->attribute->cabang_id == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cabang'))
                            <div class="small text-danger">{{ $errors->first('cabang') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tim <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="tim" class="form-select form-select-sm {{ $errors->has('tim') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($tim as $c)
                                <option value="{{ $c->id }}" {{ $pengguna->attribute->tim_id == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tim'))
                            <div class="small text-danger">{{ $errors->first('tim') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jabatan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="jabatan" class="form-select form-select-sm {{ $errors->has('jabatan') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($jabatan as $c)
                                <option value="{{ $c->id }}" {{ $pengguna->attribute->jabatan_id == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jabatan'))
                            <div class="small text-danger">{{ $errors->first('jabatan') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="email" name="email" class="form-control form-control-sm {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ $pengguna->email }}">
                            @if($errors->has('email'))
                            <div class="small text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Username <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="username" class="form-control form-control-sm {{ $errors->has('username') ? 'border-danger' : '' }}" value="{{ $pengguna->username }}">
                            @if($errors->has('username'))
                            <div class="small text-danger">{{ $errors->first('username') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Password</label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group">
                                <input type="password" name="password" class="form-control form-control-sm {{ $errors->has('password') ? 'border-danger' : '' }}">
                                <button type="button" class="btn btn-sm {{ $errors->has('password') ? 'btn-outline-danger' : 'btn-outline-secondary' }} btn-toggle-password"><i class="bi-eye"></i></button>
                            </div>
                            <div class="small text-muted">Kosongi saja jika tidak ingin mengganti password</div>
                            @if($errors->has('password'))
                            <div class="small text-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('pic'.$role) }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection
