@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit Petugas')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Petugas</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.petugas.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $petugas->id }}">
                    <input type="hidden" name="tipe_id" value="{{ $petugas->attribute->tipe_id }}">
                    <input type="hidden" name="vendor_id" value="{{ $petugas->attribute->vendor_id }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="nama" class="form-control form-control-sm {{ $errors->has('nama') ? 'border-danger' : '' }}" value="{{ $petugas->name }}" autofocus>
                            @if($errors->has('nama'))
                            <div class="small text-danger">{{ $errors->first('nama') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Vendor <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="vendor" class="form-select form-select-sm {{ $errors->has('vendor') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($tipe as $t)
                                <optgroup label="{{ $t->nama }}">
                                    @foreach($t->vendor as $v)
                                    <option value="{{ $v->id }}-{{ $t->id }}" {{ $petugas->attribute->vendor_id == $v->id && $petugas->attribute->tipe_id == $t->id ? 'selected' : '' }}>{{ $v->nama }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            @if($errors->has('vendor'))
                            <div class="small text-danger">{{ $errors->first('vendor') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Cabang <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="cabang" class="form-select form-select-sm {{ $errors->has('cabang') ? 'border-danger' : '' }}" {{ Auth::user()->role_id == role('admin-cabang') ? 'disabled' : '' }}>
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($cabang as $c)
                                <option value="{{ $c->id }}" {{ $petugas->attribute->cabang_id == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cabang'))
                            <div class="small text-danger">{{ $errors->first('cabang') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Username <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="username" class="form-control form-control-sm {{ $errors->has('username') ? 'border-danger' : '' }}" value="{{ $petugas->username }}">
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
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Foto</label>
                        <div class="col-lg-10 col-md-9">
                            <div>
                                <input type="file" name="photo" accept="image/*">
                            </div>
                            <div>
                                <input type="hidden" name="photo_source">
                                <img src="{{ asset('assets/images/users/'.$petugas->avatar) }}" width="175" class="photo img-thumbnail rounded-circle mt-3 {{ $petugas->avatar != '' && File::exists(public_path('assets/images/users/'.$petugas->avatar)) ? '' : 'd-none' }}">
                            </div>
                            @if($errors->has('photo'))
                            <div class="small text-danger">{{ $errors->first('photo') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.petugas.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

<div class="modal fade" id="modal-croppie" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sesuaikan Foto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Ukuran 250 x 250 pixel.</p>
                <div class="table-responsive">
                    <div id="croppie"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary btn-croppie">Potong</button>
                <button class="btn btn-sm btn-danger" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    // Select2
    Spandiv.Select2("select[name=cabang]");

    // Change Vendor
    $(document).on("change", "select[name=vendor]", function() {
        var value = $(this).val();
        var split = value.split("-");
        $("input[name=vendor_id]").val(split[0]);
        $("input[name=tipe_id]").val(split[1]);
    });

    // Init Croppie
    var croppie = Spandiv.Croppie("#croppie", {
        width: 250,
        height: 250,
        type: 'circle'
    });

    // Change Croppie Input
    $(document).on("change", "input[name=photo]", function() {
        Spandiv.CroppieBindFromURL(croppie, this);
        Spandiv.Modal("#modal-croppie").show();
    });

    // Button Croppie
    $(document).on("click", ".btn-croppie", function(e) {
        e.preventDefault();
        croppie.croppie('result', {
            type: 'base64',
            circle: false
        }).then(function(response) {
            $("img.photo").attr("src",response).removeClass("d-none");
            $("input[name=photo_source]").val(response);
            $("input[name=photo]").val(null);
            Spandiv.Modal("#modal-croppie").hide();
        });
    });
</script>

@endsection

@section('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">

@endsection