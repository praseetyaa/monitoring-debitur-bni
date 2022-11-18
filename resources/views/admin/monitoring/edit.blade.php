@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit Monitoring')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Monitoring</h1>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Nama:</div>
                        <p class="mb-0">{{ $monitoring->user->name }}</p>
                    </div>
                    @if($monitoring->user->role_id == role('pegawai'))
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">NPP:</div>
                        <p class="mb-0">{{ $monitoring->user->attribute->npp }}</p>
                    </div>
                    @endif
                    @if($monitoring->user->role_id == role('petugas'))
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Nama Vendor:</div>
                        <p class="mb-0">{{ $monitoring->user->attribute->vendor->nama }}</p>
                    </div>
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Tipe Vendor:</div>
                        <p class="mb-0">
                            {{ $monitoring->user->attribute->tipe->nama }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.monitoring.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $monitoring->id }}">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <p class="form-label">Tanggal <span class="text-danger">*</span></p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ date('d/m/Y', strtotime($monitoring->created_at)) }}" readonly>
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <p class="form-label">Jam <span class="text-danger">*</span></p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ date('H:i', strtotime($monitoring->created_at)) }}" readonly>
                                <span class="input-group-text"><i class="bi bi-alarm"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                        <p class="form-label">Cabang<span class="text-danger">*</span></p>
                            <div class="input-group">
                                <input type="text" name="cabang" class="form-control" value="{{ $monitoring->cabang->nama }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <p class="form-label">Nama ATM<span class="text-danger">*</span></p>
                            <select class="form-select form-select-sm {{ $errors->has('atm') ? 'border-danger' : '' }}" name="atm">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($atm as $a)
                                <option value="{{ $a->id }}" data-id="{{ $a->id_atm }}" data-nama="{{ $a->nama }}" {{ $monitoring->atm->id == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('atm'))
                            <div class="small text-danger">{{ $errors->first('atm') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-4 mb-3">
                        <p class="form-label">ID ATM<span class="text-danger">*</span></p>
                            <div class="input-group">
                                <input type="text" name="id_atm" class="form-control form-control-sm {{ $errors->has('id_atm') ? 'border-danger' : '' }}" value="{{ $monitoring->atm->id_atm }}" readonly>
                                @if($errors->has('id_atm'))
                                <div class="small text-danger">{{ $errors->first('id_atm') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- <p class="fw-bold">Location</p> -->
                        <!-- <iframe class="mb-3 " id="maps" src="//maps.google.com/maps?q={{ $monitoring->latitude }},{{ $monitoring->longitude }}&z=15&output=embed"></iframe> -->
                        <div class="col-lg-6 d-none">
                            <label class="form-label fw-bold">Long</label>
                            <input type="text" name="long" id="long" class="form-control" value="{{ $monitoring->longitude }}" readonly>
                        </div>
                        <div class="col-lg-6 d-none">
                            <label class="form-label fw-bold">Lat</label>
                            <input type="text" name="lat" id="lat" class="form-control" value="{{ $monitoring->latitude }}" readonly>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label class="form-label fw-bold">Lokasi</label>
                            <textarea name="lokasi" id="lokasi" class="form-control" rows="3" readonly>{{ $monitoring->lokasi }}</textarea>
                        </div>
                    </div>

                    <hr class="my-5">
                    <div class="d-flex justify-content-center"><h3 class="position-relative bg-white text-uppercase px-2" style="top:-3.8rem; width:fit-content;">Interior</h3></div>
                    
                    <div class="row">
                        @foreach($kategori_interior as $k)
                            @if($k->contoh == '')
                            <div class="col-lg-3 mb-5">
                                <p class="fw-bold">{{ $k->awal }} {{ $k->akhir }}?<span class="text-danger">*</span></p>
                            @else
                                @if(is_int(strpos($k->contoh, ', ')))
                                <div class="col-lg-12 mb-5">
                                    <?php $contoh = explode(', ', $k->contoh); ?>
                                    <p class="fw-bold">{{ $k->awal }} {{ $k->akhir }}?<span class="text-danger">*</span></p>
                                    <p>Contoh:</p>
                                    <div class="row">
                                        @foreach($contoh as $c)
                                        <div class="col-lg">
                                            <img src="{{ asset('assets/images/input/'.$c) }}" alt="" class="img-fluid rounded-3 mb-3">
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                <div class="col-lg-4 mb-5">
                                    <p class="fw-bold">{{ $k->awal }} {{ $k->akhir }}?<span class="text-danger">*</span></p>
                                    <img src="{{ asset('assets/images/input/'.$k->contoh) }}" alt="" class="img-fluid rounded-3 mb-3">
                                @endif
                            @endif
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-1" value="1" {{ $monitoring->detail()->where('kategori_id','=',$k->id)->first()->jawaban == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="radio-{{ $k->id }}-1">{{ $k->opsi_ya != '' ? $k->opsi_ya : 'Ya' }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-0" value="0" {{ $monitoring->detail()->where('kategori_id','=',$k->id)->first()->jawaban == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="radio-{{ $k->id }}-0">{{ $k->opsi_tidak != '' ? $k->opsi_tidak : 'Tidak' }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-5">
                    <div class="d-flex justify-content-center"><h3 class="position-relative bg-white text-uppercase px-2" style="top:-3.8rem; width:fit-content;">Eksterior</h3></div>
                    <div class="row">
                        @foreach($kategori_eksterior as $k)
                            @if($k->contoh == '')
                            <div class="col-lg-3 mb-5">
                                <p class="fw-bold">{{ $k->awal }} {{ $k->akhir }}?<span class="text-danger">*</span></p>
                            @else
                                @if(is_int(strpos($k->contoh, ', ')))
                                <div class="col-lg-12 mb-5">
                                    <?php $contoh = explode(', ', $k->contoh); ?>
                                    <p class="fw-bold">{{ $k->awal }} {{ $k->akhir }}?<span class="text-danger">*</span></p>
                                    <p>Contoh:</p>
                                    <div class="row">
                                        @foreach($contoh as $c)
                                        <div class="col-lg">
                                            <img src="{{ asset('assets/images/input/'.$c) }}" alt="" class="img-fluid rounded-3 mb-3">
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                <div class="col-lg-4 mb-5">
                                    <p class="fw-bold">{{ $k->awal }} {{ $k->akhir }}?<span class="text-danger">*</span></p>
                                    <img src="{{ asset('assets/images/input/'.$k->contoh) }}" alt="" class="img-fluid rounded-3 mb-3">
                                @endif
                            @endif
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-1" value="1" {{ $monitoring->detail()->where('kategori_id','=',$k->id)->first()->jawaban == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="radio-{{ $k->id }}-1">{{ $k->opsi_ya != '' ? $k->opsi_ya : 'Ya' }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-0" value="0" {{ $monitoring->detail()->where('kategori_id','=',$k->id)->first()->jawaban == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="radio-{{ $k->id }}-0">{{ $k->opsi_tidak != '' ? $k->opsi_tidak : 'Tidak' }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Tambahan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan Catatan">{{ $monitoring->catatan }}</textarea>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9 text-end">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.monitoring.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection

@section('js')

<script>
    Spandiv.Select2('select[name="atm"]');
    // Change ID ATM
    $(document).on("change", "select[name=atm]", function() {
        var value = $(this).val();
        var option = $(this).find("option[value=" + value + "]");
        var id = $(option).data("id");
        $("input[name=id_atm]").val(id);
    });

    $(window).on("load", function() {
        buttonAction();
    });

    // Change radio
	$(document).on("change", "input[type=radio]", function() {
        buttonAction();
    });

    function buttonAction() {
        var lokasi = $("textarea[name=lokasi]").val();
        var checked = $("#form-monitoring input[type=radio]:checked").length;
        var total = $("#form-monitoring input[type=radio]").length / 2;
        if(lokasi != '' && checked == total)
            $("#btn-submit").removeAttr("disabled");
        else
            $("#btn-submit").attr("disabled","disabled");
    }
</script>

@endsection