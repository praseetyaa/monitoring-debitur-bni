@extends('faturhelper::layouts/admin/main')

@section('title', 'Tambah Monitoring')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tambah Monitoring</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDocs">Lihat Petunjuk</button>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Nama:</div>
                        <p class="mb-0">{{ Auth::user()->name }}</p>
                    </div>
                    @if(Auth::user()->role_id == role('pegawai'))
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">NPP:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->npp }}</p>
                    </div>
                    @endif
                    @if(Auth::user()->role_id == role('petugas'))
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Nama Vendor:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->vendor->nama }}</p>
                    </div>
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Tipe Vendor:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->tipe->nama }}</p>
                    </div>
                    @endif
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Cabang:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->cabang->nama }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form id="form-monitoring" method="post" action="{{ route('admin.monitoring.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                        <p class="form-label">Cabang<span class="text-danger">*</span></p>
                            <div class="input-group">
                                <input type="text" name="cabang" class="form-control form-control-sm {{ $errors->has('cabang') ? 'border-danger' : '' }}" value="{{ Auth::user()->attribute->cabang->nama }}" readonly>
                                @if($errors->has('cabang'))
                                <div class="small text-danger">{{ $errors->first('cabang') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <p class="form-label">Nama ATM<span class="text-danger">*</span></p>
                            <select class="form-select form-select-sm {{ $errors->has('atm') ? 'border-danger' : '' }}" name="atm">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($atm as $a)
                                <option value="{{ $a->id }}" data-id="{{ $a->id_atm }}" data-nama="{{ $a->nama }}" {{ old('atm') == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('atm'))
                            <div class="small text-danger">{{ $errors->first('atm') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-4 mb-3">
                        <p class="form-label">ID ATM<span class="text-danger">*</span></p>
                            <div class="input-group">
                                <input type="text" name="id_atm" class="form-control form-control-sm {{ $errors->has('id_atm') ? 'border-danger' : '' }}" value="{{ old('id_atm') }}" readonly>
                                @if($errors->has('id_atm'))
                                <div class="small text-danger">{{ $errors->first('id_atm') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- <iframe id="maps" src="//maps.google.com/maps?q=-7.0310356,110.4093417&z=15&output=embed"></iframe> -->
                        <div class="col-6 d-none">
                            <label class="form-label fw-bold">Long</label>
                            <input type="text" name="long" id="long" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-6 d-none">
                            <label class="form-label fw-bold">Lat</label>
                            <input type="text" name="lat" id="lat" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <label class="form-label fw-bold">Lokasi</label>
                            <textarea name="lokasi" class="form-control form-control-sm" rows="3" readonly></textarea>
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
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-1" value="1" {{ old('radio'.$k->id) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="radio-{{ $k->id }}-1">{{ $k->opsi_ya != '' ? $k->opsi_ya : 'Ya' }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-0" value="0" {{ old('radio'.$k->id) == '0' ? 'checked' : '' }}>
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
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-1" value="1" {{ old('radio'.$k->id) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="radio-{{ $k->id }}-1">{{ $k->opsi_ya != '' ? $k->opsi_ya : 'Ya' }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radio[{{ $k->id }}]" id="radio-{{ $k->id }}-0" value="0" {{ old('radio'.$k->id) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="radio-{{ $k->id }}-0">{{ $k->opsi_tidak != '' ? $k->opsi_tidak : 'Tidak' }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Tambahan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan Catatan"></textarea>
                        @if($errors->has('cabang'))
                        <div class="small text-danger">{{ $errors->first('cabang') }}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label fw-bold">Gambar Tambahan</label>
						<div class="row">
							<div class="col-lg-6 mb-3 mb-lg-0">
							  <div class="input-group custom-file-button">
								<label class="input-group-text" for="inputGroupFile"><i class="bi bi-file-earmark-image"></i>&nbsp;Pilih File</label>
								<input name="img[]" type="file" class="form-control" id="inputGroupFile" multiple accept="image/*">
							  </div>
							</div>
							<div class="col-lg-6">
							  <div class="input-group custom-file-button">
								<label class="input-group-text" for="inputGroupFile1"><i class="bi bi-camera-fill"></i>&nbsp;Ambil Gambar</label>
								<input name="img[]" type="file" class="form-control" id="inputGroupFile1" multiple accept="image/*" capture="camera">
							  </div>
							</div>
						</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9 text-end">
                            <button type="submit" class="btn btn-sm btn-primary" id="btn-submit" disabled><i class="bi-save me-1"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

<!-- modal -->
<div id="modalDocs" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Petunjuk Pengamanan ATM</h5>
        <button type="button" class="btn-close d-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="theDocs">
        @foreach(File::allFiles(public_path('assets/pdf')) as $file)
            <img src="{{ asset('assets/pdf/'.$file->getRelativePathname()) }}" class="img-fluid">
        @endforeach
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="check-me" disabled>
            <label class="form-check-label" for="check-me">
                Saya telah membaca panduan
            </label>
        </div>
        <button type="button" class="btn btn-primary btn-understand" data-bs-dismiss="modal" disabled>Saya Mengerti</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('css')
<style>
.custom-file-button input[type=file] {
  margin-left: -2px !important;
}

.custom-file-button input[type=file]::-webkit-file-upload-button {
  display: none;
}

.custom-file-button input[type=file]::file-selector-button {
  display: none;
}

.custom-file-button:hover label {
  background-color: #dde0e3;
  cursor: pointer;
}				
</style>
@endsection
				
@section('js')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js" integrity="sha512-Z8CqofpIcnJN80feS2uccz+pXWgZzeKxDsDNMD/dJ6997/LSRY+W4NmEt9acwR+Gt9OHN0kkI1CTianCwoqcjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script>
    let Longitude = 0, Latitude = 0;
    getLocation();
    Spandiv.Select2('select[name="atm"]');

    // Change ID ATM
    $(document).on("change", "select[name=atm]", function() {
        var value = $(this).val();
        var option = $(this).find("option[value=" + value + "]");
        var id = $(option).data("id");
        $("input[name=id_atm]").val(id);
    });

    // Get Current Location 
    function getLocation() {
        if(navigator.geolocation) {
			window.setInterval(() => {
				navigator.geolocation.getCurrentPosition(showPosition);
			}, 1000);
        } else { 
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
		document.getElementById("long").value = position.coords.longitude;
		document.getElementById("lat").value = position.coords.latitude;
        if(Longitude != position.coords.longitude.toFixed(3) || Latitude != position.coords.latitude.toFixed(3)) {
            getLocationName(position.coords.longitude, position.coords.latitude);
            Longitude = position.coords.longitude.toFixed(3);
            Latitude = position.coords.latitude.toFixed(3);
        }
    }

    function getLocationName(lon, lat) {
        $.ajax({
            type: 'get',
            url: 'https://nominatim.openstreetmap.org/reverse',
            data: {format: 'jsonv2', lon: lon, lat: lat},
            success: function(response) {
                $("textarea[name=lokasi]").val(response.display_name);
                buttonAction();
            },
			error: function() {
				alert("Tidak dapat menampilkan lokasi. Mohon refresh halaman ini!");
				window.location.reload();
			}
        });
    }

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

    
    //modal
    $(window).on('load', function() {
        $('#modalDocs').modal('show');
    });

    // pdf.js
    // const url = "{{ asset('assets/docs/PETUNJUK PENGAMANAN ATM 2022 FIXED.pdf') }}";

    // const loadingTask = pdfjsLib.getDocument(url);
    // (async () => {
    //     const pdf = await loadingTask.promise;
    //     //
    //     // Fetch pages
    //     //
    //     for(var i=1; i<=pdf.numPages; i++) {
    //         const page = await pdf.getPage(i);
    //         const scale = 1.5;
    //         const viewport = page.getViewport({ scale });
    //         // Support HiDPI-screens.
    //         const outputScale = window.devicePixelRatio || 1;

    //         //
    //         // Prepare canvas using PDF page dimensions
    //         //
    //         // const canvas = document.getElementById("theDocs");
    //         const canvas = document.createElement("canvas");
    //         document.getElementById("theDocs").appendChild(canvas);
    //         canvas.classList.add("img-fluid");
    //         const context = canvas.getContext("2d");

    //         canvas.width = Math.floor(viewport.width * outputScale);
    //         canvas.height = Math.floor(viewport.height * outputScale);
    //         // canvas.style.width = Math.floor(viewport.width) + "px";
    //         // canvas.style.height = Math.floor(viewport.height) + "px";

    //         const transform = outputScale !== 1 
    //         ? [outputScale, 0, 0, outputScale, 0, 0] 
    //         : null;

    //         //
    //         // Render PDF page into canvas context
    //         //
    //         const renderContext = {
    //             canvasContext: context,
    //             transform,
    //             viewport,
    //         };
    //         page.render(renderContext);
    //     }
    // })();


    // Scroll into bottom
    $("#theDocs").scroll(function() {
        let height = 0;
        $("#theDocs").find("img").each(function(key,elem) {
            height += $(elem).height();
        });
        if($(this).scrollTop() > (height - $(window).height())) {
            $("#check-me").removeAttr("disabled");
        }
    });

    // Check me
    $(document).on("change", "#check-me", function() {
        $(this).prop("checked") == true ? $(".btn-understand").removeAttr("disabled") : $(".btn-understand").attr("disabled","disabled");
    });
</script>
@endsection