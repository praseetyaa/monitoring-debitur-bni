@extends('faturhelper::layouts/admin/main')

@section('title', 'Pengaturan Profil')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Pengaturan Profil</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3 mb-3 mb-md-0">
        <div class="list-group">
            <a href="{{ route('admin.settings.profile') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.settings.profile'))) ? 'active' : '' }}">Profil</a>
            <a href="{{ route('admin.settings.account') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.settings.account'))) ? 'active' : '' }}">Akun</a>
            <a href="{{ route('admin.settings.password') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.settings.password'))) ? 'active' : '' }}">Password</a>
        </div>
    </div>
	<div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form method="post" action="{{ route('admin.settings.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-lg-3 col-md-4 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-9 col-md-8">
                            <input type="text" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'border-danger' : '' }}" value="{{ Auth::user()->name }}" autofocus>
                            @if($errors->has('name'))
                            <div class="small text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-3 col-md-4 col-form-label">NPP <span class="text-danger">*</span></label>
                        <div class="col-lg-9 col-md-8">
                            <input type="text" name="npp" class="form-control form-control-sm {{ $errors->has('npp') ? 'border-danger' : '' }}" value="{{ $user->attribute->npp }}" autofocus>
                            @if($errors->has('npp'))
                            <div class="small text-danger">{{ $errors->first('npp') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-3 col-md-4 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="col-lg-9 col-md-8">
                            @foreach(gender() as $gender)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender-{{ $gender['key'] }}" value="{{ $gender['key'] }}" {{ Auth::user()->attribute && Auth::user()->attribute->gender == $gender['key'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender-{{ $gender['key'] }}">
                                    {{ $gender['name'] }}
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('gender'))
                                <div class="small text-danger">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-md-4 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-9 col-md-8">
                            <input type="email" name="email" class="form-control form-control-sm {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ Auth::user()->email }}" autofocus>
                            @if($errors->has('email'))
                            <div class="small text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-md-4 col-form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <div class="col-lg-9 col-md-8">
                            <div class="input-group">
                                <select name="country_code" class="form-select form-select-sm {{ $errors->has('country_code') ? 'border-danger' : '' }}" id="select2" style="width: 40%"></select>
                                <input type="text" name="phone_number" class="form-control form-control-sm {{ $errors->has('phone_number') ? 'border-danger' : '' }}" value="{{ Auth::user()->attribute ? Auth::user()->attribute->phone_number : '' }}">
                            </div>
                            @if($errors->has('phone_number'))
                            <div class="small text-danger">{{ $errors->first('phone_number') }}</div>
                            @elseif($errors->has('country_code'))
                            <div class="small text-danger">{{ $errors->first('country_code') }}</div>
                            @endif
                        </div>
                    </div>
                    @if(Auth::user()->role_id == 5)
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-4 col-form-label">Jabatan <span class="text-danger">*</span></label>

                            <div class="col-lg-9 col-md-8">
                                <div class="input-group">
                                    <select name="jabatan_id" class="form-select form-select-sm {{ $errors->has('jabatan_id') ? 'border-danger' : '' }}" id="select2" style="width: 40%">
                                            <option value="" disabled>Jabatan</option>
                                        @foreach ($jabatan as $item)
                                            <option {{ Auth::user()->attribute ? (Auth::user()->attribute->jabatan_id == $item->id ? 'selected' : '') : '' }} value="{{$item->id}}">{{$item->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('jabatan_id'))
                                    <div class="small text-danger">{{ $errors->first('jabatan_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-md-4 col-form-label">Cabang <span class="text-danger">*</span></label>
                            <div class="col-lg-9 col-md-8">
                                <div class="input-group">
                                    <select name="cabang_id" class="form-select form-select-sm {{ $errors->has('cabang_id') ? 'border-danger' : '' }}" id="select2" style="width: 40%">
                                            <option value="" disabled>Cabang</option>
                                        @foreach ($cabang as $item)
                                            <option {{ Auth::user()->attribute ? (Auth::user()->attribute->cabang_id == $item->id ? 'selected' : '') : '' }} value="{{$item->id}}">{{$item->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('cabang_id'))
                                    <div class="small text-danger">{{ $errors->first('cabang_id') }}</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div class="row">
                        <div class="col-lg-3 col-md-4"></div>
                        <div class="col-lg-9 col-md-8">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // Get Country Codes
    Spandiv.Select2ServerSide("#select2", {
        url: "{{ route('api.country-code') }}",
        value: "{{ Auth::user()->attribute ? Auth::user()->attribute->country_code : '' }}",
        valueProp: "code",
        nameProp: "name",
        bracketProp: "dial_code"
    });

    // Datepicker
    Spandiv.DatePicker("input[name=birthdate]");
</script>

@endsection
