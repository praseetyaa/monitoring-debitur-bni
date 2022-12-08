@extends('faturhelper::layouts/admin/main')

@section('title', 'Detail Data')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Detail Data</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                    @if(Session::get('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-message">{{ Session::get('message') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div><span class="fw-bold">Solicit Dibuat Oleh</span><br>{{$data->nama_input}}<br>{{date('d M Y H:i:s', strtotime($data->created_at))}}</div>
                                </div>
                                @if($data->status_debitur > 1)
                                    <div class="col-lg-3 mb-3">
                                        <div><span class="fw-bold">Solicit Diverifikasi Oleh</span><br>{{$data->nama_verif}}<br>{{date('d M Y H:i:s', strtotime($data->tanggal_verif))}}</div>
                                    </div>
                                @endif
                                @if($data->status_debitur > 2)
                                    <div class="col-lg-3 mb-3">
                                        <div><span class="fw-bold">Solicit DiApprove Oleh</span><br>{{$data->nama_approve}}<br>{{date('d M Y H:i:s', strtotime($data->tanggal_approve))}}</div>
                                    </div>
                                @endif
                                @if($data->status_debitur > 3)
                                    <div class="col-lg-3 mb-3">
                                        <div><span class="fw-bold">Prospek Diupdate Oleh</span><br>{{$data->nama_update_prospek}}<br>{{date('d M Y H:i:s', strtotime($data->tanggal_update_prospek))}}</div>
                                    </div>
                                @endif
                                @if($data->status_debitur < 1)
                                    <div class="col-lg-3 mb-3">
                                        <div><span class="fw-bold">Ditolak Oleh</span><br>{{$data->nama_penolak}}<br>{{date('d M Y H:i:s', strtotime($data->tanggal_penolakan))}}</div>
                                    </div>
                                @endif
                                @if(Auth::user()->role_id == 6 || Auth::user()->role_id == 3)
                                    <div class="col-lg-3 mb-3">
                                        <div><span class="fw-bold">Hubungi Inputer</span><br><a href="https://wa.me/{{$data->picinputer->attribute->phone_number}}">{{$data->nama_input}} | {{$data->picinputer->attribute->phone_number}}</a></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-{{ $data->statusdebitur->color }}" role="alert">
                                <div class="alert-message align-items-center text-center" style="text-align: center!important;">
                                    {{ $data->statusdebitur->narasi }}
                                </div>
                            </div>
                        </div>
                        @if($data->status_debitur < 1)
                            <div class="col-md-12">
                                <label class="mb-2" style="font-weight: bold;">Alasan Penolakan</label>
                                <textarea class="form-control" rows="5" disabled>{{ $data->alasantolak }}</textarea>
                            </div>
                        @endif
                    </div>
                <form enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="mb-2" style="font-weight: bold">Nama Debitur</label>
                            <input disabled placeholder="Nama Debitur" type="text" name="nama_debitur" class="form-control" value="{{ $data->nama_debitur }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Bidang Usaha</label>
                            <input disabled type="text" name="bidang_usaha" id="bidang_usaha" class="form-control" value="{{ $data->bidang_usaha }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Sektor</label>
                            <input disabled type="text" name="sektor" id="sektor" class="form-control" value="{{ $data->sektor }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kategori</label>
                            <input disabled type="text" name="kategori" id="kategori" class="form-control" value="{{ $data->kategori }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Orientasi Ekspor</label>
                            <input disabled type="text" name="orientasiekspor" id="orientasiekspor" class="form-control" value="{{ $data->orientasiekspor }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Indikasi Kebutuhan Produk/Jasa</label>
                            <input disabled type="text" name="indikasi_kebutuhan_produk" id="indikasi_kebutuhan_produk" class="form-control" value="{{ $data->indikasi_kebutuhan_produk }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Sumber</label>
                            <input disabled type="text" name="sumber" id="sumber" class="form-control" value="{{ $data->sumber }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Data Leads</label>
                            <input readonly placeholder="Data Leads" type="text" name="dataleads" id="dataleads" class="form-control" value="{{ $data->dataleads }}" autofocus>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2 mt-2">
                            <a target="_blank" href="{{ route('openfile', ['path' => $data->dokumen_lokasi]) }}" class="btn btn-sm btn-primary w-100">Foto Lokasi</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Latitude</label>
                            <input disabled type="text" name="latitude" id="latitude" class="form-control" value="{{ $data->latitude }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Longitude</label>
                            <input disabled type="text" name="longitude" id="longitude" class="form-control" value="{{ $data->longitude }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Provinsi</label>
                            <input disabled type="text" name="provinsi" id="provinsi" class="form-control" value="{{ $data->provinsi }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kabupaten/Kota</label>
                            <input disabled type="text" name="kota" id="kota" class="form-control" value="{{ $data->kota }}" autofocus>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kecamatan</label>
                            <input disabled type="text" name="kecamatan" id="kecamatan" class="form-control" value="{{ $data->kecamatan }}" autofocus>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Desa</label>
                            <input disabled type="text" name="desa" id="desa" class="form-control" value="{{ $data->desa }}" autofocus>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kode Pos</label>
                            <input disabled type="text" name="kodepos" id="kodepos" class="form-control" value="{{ $data->kodepos }}" autofocus>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Detail Alamat</label>
                            <textarea disabled placeholder="RT/RW, Jalan, Nomor Rumah" name="detail_alamat" id="detail_alamat" class="form-control" autofocus>{{ $data->detail_alamat }}</textarea>
                        </div>
                    </div>
                        @if($data->status_debitur > 1)
                            <div class="row">
                                <div class="col-md-4 mb-2 mt-2 text-center">
                                    <input type="checkbox" {{$data->pre_screen == 1 ? 'checked' : ''}} disabled>
                                    <label style="font-weight: bold"> Pre Screen</label><br>
                                </div>
                                <div class="col-md-4 mb-2 mt-2 text-center">
                                    <input type="checkbox" {{$data->ots_penyelia == 1 ? 'checked' : ''}} disabled>
                                    <label style="font-weight: bold"> OTS Penyelia</label><br>
                                </div>
                                <div class="col-md-4 mb-2 mt-2 text-center">
                                    <input type="checkbox" {{$data->ots_pemimpin == 1 ? 'checked' : ''}} disabled>
                                    <label style="font-weight: bold"> OTS Pemimpin</label><br>
                                </div>
                            </div>
                        @endif
                        @if($data->status_debitur > 3)
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="mb-2" style="font-weight: bold">Nominal Usulan</label>
                                    <input disabled placeholder="Nominal Usulan" type="text" class="form-control" value="{{$data->nominal_usulan}}" autofocus>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="mb-2"  style="font-weight: bold">Jenis Fasilitas</label>
                                    <input disabled placeholder="Jenis Fasilitas" type="text" class="form-control" value="{{$data->jenis_fasilitas}}" autofocus>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="mb-2"  style="font-weight: bold">Skim</label>
                                    <input disabled placeholder="Skim" type="text" class="form-control" value="{{$data->skim}}" autofocus>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="mb-2"  style="font-weight: bold">Kewenangan Komite</label>
                                    <input disabled placeholder="Kewenangan" type="text" class="form-control" value="{{$data->kewenangan_komite}}" autofocus>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="mb-2"  style="font-weight: bold">Tanggal Analisa</label>
                                    <input disabled placeholder="Tanggal Analisa" type="text" class="form-control" value="{{$data->tanggal_analisa}}" autofocus>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="mb-2"  style="font-weight: bold">Tanggal Komite</label>
                                    <input disabled placeholder="Tanggal Komite" type="text" class="form-control" value="{{$data->tanggal_komite}}" autofocus>
                                </div>
                            </div>
                        @endif
                    <hr>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if(Auth::user()->role_id == 6)
                                @if($data->status_debitur == 1)
                                    <a onclick="deny()" class="btn btn-warning">Tolak Solicit</a>
                                    <a class="btn btn-primary" onclick="openmodal('mdlverifikasi')">Verifikasi Solicit</a>
                                @endif
                            @endif

                            @if(Auth::user()->role_id == 3)
                                @if($data->status_debitur == 2)
                                    <a onclick="deny()" class="btn btn-warning">Tolak Solicit</a>
                                    <a class="btn btn-primary" onclick="openmodal('mdlappsolicit')">Approval Solicit</a>
                                @endif
                            @endif

                            @if(Auth::user()->role_id == 4)
                                @if($data->status_debitur == 3)
                                    <a class="btn btn-primary" onclick="openmodal('mdlprospekdata')">Prospek Data</a>
                                @endif

                                @if($data->status_debitur == 4)
                                <a class="btn btn-primary" onclick="openmodal('mdlpipelinedata')">Tindak Lanjut Pipeline</a>
                            @endif
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

<div class="modal fade" id="mdlverifikasi" tabindex="-1" aria-labelledby="mdlverifikasi" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="ModalShowListLabel">Verifikasi Data</h5>
                <button type="button" class="btn btn-sm btn-primary" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('verifisolicit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            Apakah anda yakin ingin memverifikasi data ini?
                        </div>
                        <div class="col-4 mb-2">
                        </div>
                        <div class="col-8 mb-2">
                            <input required type="checkbox" name="pre_screen" value="1">
                            <label for="pre_screen" style="font-weight: bold"> Pre Screen</label><br>
                            <input required type="checkbox" name="ots_penyelia" value="1">
                            <label for="ots_penyelia" style="font-weight: bold"> OTS Penyelia</label><br>
                            <input required type="checkbox" name="ots_pemimpin" value="1">
                            <label for="ots_pemimpin" style="font-weight: bold"> OTS Pemimpin</label><br>
                        </div>
                        <div class="col-md-12 mb-2 mt-2 text-center">
                            <button type="submit" class="btn btn-primary">Ya, Verifikasi Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlappsolicit" tabindex="-1" aria-labelledby="mdlappsolicit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="ModalShowListLabel">Approval Data</h5>
                <button type="button" class="btn btn-sm btn-primary" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2 text-center">
                        Apakah anda yakin ingin menyetujui solicit ini?
                    </div>
                </div>
                <form method="post" action="{{ route('appsolicit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="row">
                        <div class="col-md-12 mb-2 mt-2 text-center">
                            <button type="submit" class="btn btn-primary">Ya, Setujui</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlprospekdata" tabindex="-1" aria-labelledby="mdlverifikasi" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="ModalShowListLabel">Prospek Data</h5>
                <button type="button" class="btn btn-sm btn-primary" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('prospekdata') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="mb-2" style="font-weight: bold">Nominal Usulan</label>
                            <input required placeholder="Nominal Usulan" type="text" id="nominal_usulan" name="nominal_usulan" class="form-control" value="" autofocus>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Jenis Fasilitas</label>
                            <select required name="jenis_fasilitas" id="jenis_fasilitas" class="form-select">
                                <option value="" disabled selected>-- Pilih Jenis Fasilitas --</option>
                                @foreach($mst_jenis_fasilitas as $c)
                                    <option value="{{ $c->jenis_fasilitas }}">{{ $c->jenis_fasilitas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Skim</label>
                            <select required name="skim" id="skim" class="form-select">
                                <option value="" disabled selected>-- Pilih Skim --</option>
                                @foreach($mst_skim as $c)
                                    <option value="{{ $c->skim }}">{{ $c->skim }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kewenangan Komite</label>
                            <select required name="kewenangan_komite" id="kewenangan_komite" class="form-select">
                                <option value="" disabled selected>-- Pilih Kewenangan --</option>
                                <option value="Komite SBE/STA">Komite SBE/STA</option>
                                <option value="Komite Wilayah">Komite Wilayah</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Tanggal Analisa</label>
                            <input required type="date" class="form-control" name="tanggal_analisa">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Tanggal Komite</label>
                            <input required type="date" class="form-control" name="tanggal_komite">
                        </div>
                        <div class="col-md-12 mb-2 mt-2 text-center">
                            <button type="submit" class="btn btn-primary">Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdldenydata" tabindex="-1" aria-labelledby="mdldenydata" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Tolak Data</h5>
                <button type="button" class="btn btn-sm btn-primary" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formdenydata" method="post" action="{{ route('solicitdeny') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            Apakah anda yakin ingin menolak data ini?
                        </div>
                        <div class="col-12 mb-2">
                            <label style="font-weight:bold">Alasan Penolakan</label>
                            <textarea class="form-control" required name="alasantolak" placeholder="Alasan Penolakan" rows="5"></textarea>
                        </div>
                        <div class="col-md-12 mb-2 mt-2 text-center">
                            <button type="submit" class="btn btn-primary">Ya, Tolak Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="{{asset('/')}}ajaxlib.js"></script>
<script>
    function openmodal(id)
    {
        $('#'+id).modal('show')
    }

    function deny()
    {
        $("#mdldenydata").modal('show');
    }

    $('#nominal_usulan').on('input', function(){
        $('#nominal_usulan').val(formatRupiah(this.value));
    });
    function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@endsection
