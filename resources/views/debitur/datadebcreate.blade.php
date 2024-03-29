@extends('faturhelper::layouts/admin/main')

@section('title', 'Tambah Solicit')

@section('content')
<script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="{{asset('/')}}ajaxlib.js"></script>
<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Tambah Solicit</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('solicitstore') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="mb-2" style="font-weight: bold">Nama Debitur<span class="text-danger">*</span></label>
                            <input required placeholder="Nama Debitur" type="text" name="nama_debitur" class="form-control {{ $errors->has('nama_debitur') ? 'border-danger' : '' }}" value="{{ old('nama_debitur') }}" autofocus>
                            @if($errors->has('nama_debitur'))
                                <div class="small text-danger">{{ $errors->first('nama_debitur') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Bidang Usaha<span class="text-danger">*</span></label>
                            <input required placeholder="Bidang Usaha" type="text" name="bidang_usaha" class="form-control {{ $errors->has('bidang_usaha') ? 'border-danger' : '' }}" value="{{ old('bidang_usaha') }}" autofocus>
                            @if($errors->has('bidang_usaha'))
                                <div class="small text-danger">{{ $errors->first('bidang_usaha') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Sektor<span class="text-danger">*</span></label>
                            <select required name="sektor" class="form-select {{ $errors->has('sektor') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Sektor--</option>
                                @foreach($sektor as $c)
                                <option value="{{ $c->nama_sektor }}" {{ old('sektor') == $c->id ? 'selected' : '' }}>{{ $c->nama_sektor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('sektor'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kategori<span class="text-danger">*</span></label>
                            <select required name="kategori" class="form-select {{ $errors->has('kategori') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Kategori--</option>
                                <option value="Pemain Utama">Pemain Utama</option>
                                <option value="Pemain Non Utama">Pemain Non Utama</option>
                            </select>
                            @if($errors->has('kategori'))
                            <div class="small text-danger">{{ $errors->first('kategori') }}</div>
                            @endif
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Orientasi Ekspor<span class="text-danger">*</span></label>
                            <select required name="orientasiekspor" class="form-select {{ $errors->has('orientasiekspor') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Orientasi Ekspor--</option>
                                <option value="Ya (Orientasi Ekspor)">Ya (Orientasi Ekspor)</option>
                                <option value="Tidak (non Orientasi Ekspor)">Tidak (non Orientasi Ekspor)</option>
                            </select>
                            @if($errors->has('orientasiekspor'))
                            <div class="small text-danger">{{ $errors->first('orientasiekspor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Indikasi Kebutuhan Produk/Jasa<span class="text-danger">*</span></label>
                            <select required name="indikasi_kebutuhan_produk" id="indikasi" class="form-select {{ $errors->has('indikasi_kebutuhan_produk') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Indikasi Kebutuhan Produk/Jasa--</option>
                                <option value="Kredit Produkif">Kredit Produkif</option>
                                <option value="Kredit Konsumer">Kredit Konsumer</option>
                                <option value="Dana">Dana</option>
                                <option value="Layanan Transaksi Lain">Layanan Transaksi Lain</option>
                            </select>
                            @if($errors->has('indikasi_kebutuhan_produk'))
                            <div class="small text-danger">{{ $errors->first('indikasi_kebutuhan_produk') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Layanan Transaksi Lain<span class="text-danger reqlayanantransaksilain" style="display: none">*</span></label>
                            <input readonly placeholder="Layanan Transaksi Lain" type="text" name="layanantransaksilain" id="layanantransaksilain" class="form-control {{ $errors->has('layanantransaksilain') ? 'border-danger' : '' }}" value="{{ old('layanantransaksilain') }}" autofocus>
                            @if($errors->has('layanantransaksilain'))
                                <div class="small text-danger">{{ $errors->first('layanantransaksilain') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Sumber<span class="text-danger">*</span></label>
                            <select required name="sumber" id="sumber" class="form-select {{ $errors->has('sumber') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Sumber--</option>
                                @foreach($sumber as $c)
                                <option value="{{ $c->nama_sumber }}" {{ old('sumber') == $c->id ? 'selected' : '' }}>{{ $c->nama_sumber }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('sumber'))
                            <div class="small text-danger">{{ $errors->first('sumber') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Data Leads<span class="text-danger reqdataleads" style="display: none">*</span></label>
                            <input readonly placeholder="Data Leads" type="text" name="dataleads" id="dataleads" class="form-control {{ $errors->has('dataleads') ? 'border-danger' : '' }}" value="{{ old('dataleads') }}" autofocus>
                            @if($errors->has('dataleads'))
                                <div class="small text-danger">{{ $errors->first('dataleads') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>

                    <!-- ================================================== Upload Gambar ================================================== -->

                        <input required type="hidden" class="form-control" id="jumlah_foto" name="jumlah_foto" value="1">
                        <div class="row" id="cont_image_rep_1">
                             <div class="col-md-12">
                                <label class="mb-2" style="font-weight: bold" id="titlefotolok_rep_1">Foto Lokasi Dari Kamera<span class="text-danger" style="display: none">*</span></label>

                                <div class="input-group mb-3">
                                    <input required type="file" class="form-control" id="foto_lokasi_rep_1" name="foto_lokasi_rep_1" accept="image/*" capture="camera">
                                    <div class="input-group-append bg-primary">
                                        <a class="btn btn-primary" id="btnchangecapture_rep_1" onclick="inputfromgalery(1)"><i class="bi bi-archive-fill"></i> From File  &nbsp;</a>
                                    </div>
                                  </div>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <a onclick="KurangiGambar()" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Kurangi Gambar</a>
                                <a onclick="TambahGambar()" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Tambah Gambar</a>
                            </div>
                        </div>
                        <script>
                            var jumlahgambar = 1;
                            $(document).ready(function(){
                                if(detectMob())
                                {
                                    inputfromkamera()
                                }
                                else
                                {
                                    inputfromgalery()
                                }
                            })

                            function TambahGambar()
                            {
                                jumlahgambar += 1;
                                $('#cont_image_rep_'+(jumlahgambar-1)).after(`
                                    <div class="row" id="cont_image_rep_`+jumlahgambar+`">
                                        <div class="col-md-12">
                                            <label class="mb-2" style="font-weight: bold" id="titlefotolok_rep_`+jumlahgambar+`">Foto Lokasi Dari Kamera<span class="text-danger" style="display: none">*</span></label>
                                            <div class="input-group mb-3">
                                                <input required type="file" class="form-control" id="foto_lokasi_rep_`+jumlahgambar+`" name="foto_lokasi_rep_`+jumlahgambar+`" accept="image/*" capture="camera">
                                                <div class="input-group-append bg-primary">
                                                    <a class="btn btn-primary" id="btnchangecapture_rep_`+jumlahgambar+`" onclick="inputfromgalery(`+jumlahgambar+`)"><i class="bi bi-archive-fill"></i> From File  &nbsp;</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `)

                                $('#jumlah_foto').val(jumlahgambar)

                            }

                            function KurangiGambar()
                            {
                                if(jumlahgambar > 1)
                                {
                                    $('#cont_image_rep_'+jumlahgambar).remove()
                                    jumlahgambar -= 1;
                                    $('#jumlah_foto').val(jumlahgambar)
                                }
                            }

                            function inputfromkamera(rep)
                            {
                                $('#titlefotolok_rep_'+rep).html('Foto Lokasi Dari Kamera')
                                $('#btnchangecapture_rep_'+rep).html('<i class="bi bi-archive-fill"></i> From File  &nbsp;')
                                $('#foto_lokasi_rep_'+rep).attr('capture', 'camera')
                                $('#btnchangecapture_rep_'+rep).attr('onclick', 'inputfromgalery('+rep+')')
                            }

                            function inputfromgalery(rep)
                            {
                                $('#titlefotolok_rep_'+rep).html('Foto Lokasi Dari File')
                                $('#btnchangecapture_rep_'+rep).html('<i class="bi bi-camera-fill"></i> From Cam')
                                $('#foto_lokasi_rep_'+rep).attr('capture', 'filesystem')
                                $('#btnchangecapture_rep_'+rep).attr('onclick', 'inputfromkamera('+rep+')')
                            }

                            function detectMob() {
                                const toMatch = [
                                    /Android/i,
                                    /webOS/i,
                                    /iPhone/i,
                                    /iPad/i,
                                    /iPod/i,
                                    /BlackBerry/i,
                                    /Windows Phone/i
                                ];
                                return toMatch.some((toMatchItem) => {
                                    return navigator.userAgent.match(toMatchItem);
                                });
                            }
                        </script>

                        <!-- ================================================== Upload File Prescreen (Dokumen) ================================================== -->

                        <input required type="hidden" class="form-control" id="jumlah_dok" name="jumlah_dok" value="1">
                        <div class="row" id="cont_dok_rep_1">
                             <div class="col-md-12">
                                <label class="mb-2" style="font-weight: bold" id="titledok_rep_1">Upload File Prescreening (PDF<span class="text-danger">*</span>)<span class="text-danger" style="display: none">*</span></label>

                                <div class="input-group mb-3">
                                    <input required type="file" class="form-control" id="foto_dok_rep_1" name="foto_dok_rep_1" accept="application/pdf">
                                  </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <a onclick="KurangiGambarDok()" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Kurangi Gambar</a>
                                <a onclick="TambahGambarDok()" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Tambah Gambar</a>
                            </div>
                        </div>
                        <script>
                            var jumlahdok = 1;
                            $(document).ready(function(){
                                if(detectMob())
                                {
                                    inputfromkameradok()
                                }
                                else
                                {
                                    inputfromgalerydok()
                                }
                            })

                            function TambahGambarDok()
                            {
                                jumlahdok += 1;
                                $('#cont_dok_rep_'+(jumlahdok-1)).after(`
                                    <div class="row" id="cont_dok_rep_`+jumlahdok+`">
                                        <div class="col-md-12">
                                            <label class="mb-2" style="font-weight: bold" id="titledok_rep_`+jumlahdok+`">Upload File Prescreening<span class="text-danger" style="display: none">*</span></label>
                                            <div class="input-group mb-3">
                                                <input required type="file" class="form-control" id="foto_dok_rep_`+jumlahdok+`" name="foto_dok_rep_`+jumlahdok+`" accept="application/pdf" >
                                            </div>
                                        </div>
                                    </div>
                                `)

                                $('#jumlah_dok').val(jumlahdok)

                            }

                            function KurangiGambarDok()
                            {
                                if(jumlahdok > 1)
                                {
                                    $('#cont_dok_rep_'+jumlahdok).remove()
                                    jumlahdok -= 1;
                                    $('#jumlah_dok').val(jumlahdok)
                                }
                            }
                        </script>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            <a class="btn btn-primary btn-block" onclick="GetLocation()"><i class="bi bi-geo-alt-fill"></i> Ambil Data lokasi</a>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Latitude</label>
                            <input readonly placeholder="Latitude" type="text" name="latitude" id="latitude" class="form-control {{ $errors->has('latitude') ? 'border-danger' : '' }}" value="{{ old('latitude') }}" autofocus>
                            @if($errors->has('latitude'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Longitude</label>
                            <input readonly placeholder="Longitude" type="text" name="longitude" id="longitude" class="form-control {{ $errors->has('longitude') ? 'border-danger' : '' }}" value="{{ old('longitude') }}" autofocus>
                            @if($errors->has('longitude'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Provinsi</label>
                            <input readonly placeholder="Provinsi" type="text" name="provinsi" id="provinsi" class="form-control {{ $errors->has('provinsi') ? 'border-danger' : '' }}" value="{{ old('provinsi') }}" autofocus>
                            @if($errors->has('provinsi'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kabupaten/Kota</label>
                            <input readonly placeholder="Kabupaten/Kota" type="text" name="kota" id="kota" class="form-control {{ $errors->has('kota') ? 'border-danger' : '' }}" value="{{ old('kota') }}" autofocus>
                            @if($errors->has('kota'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kecamatan</label>
                            <input readonly placeholder="Kecamatan" type="text" name="kecamatan" id="kecamatan" class="form-control {{ $errors->has('kecamatan') ? 'border-danger' : '' }}" value="{{ old('kecamatan') }}" autofocus>
                            @if($errors->has('kecamatan'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Desa</label>
                            <input readonly placeholder="Desa" type="text" name="desa" id="desa" class="form-control {{ $errors->has('desa') ? 'border-danger' : '' }}" value="{{ old('desa') }}" autofocus>
                            @if($errors->has('desa'))
                            <div class="small text-danger">{{ $errors->first('desa') }}</div>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kode Pos</label>
                            <input readonly placeholder="Kode Pos" type="text" name="kodepos" id="kodepos" class="form-control {{ $errors->has('kodepos') ? 'border-danger' : '' }}" value="{{ old('kodepos') }}" autofocus>
                            @if($errors->has('provinsi'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Detail Alamat<span class="text-danger">*</span></label>
                            <textarea required placeholder="RT/RW, Jalan, Nomor Rumah" name="detail_alamat" id="detail_alamat" class="form-control {{ $errors->has('detail_alamat') ? 'border-danger' : '' }}" value="{{ old('detail_alamat') }}" autofocus></textarea>
                            @if($errors->has('detail_alamat'))
                                <div class="small text-danger">{{ $errors->first('detail_alamat') }}</div>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            <button type="submit" class="btn btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('DataSol') }}" class="btn btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

<script>
    var DataLat     = '';
    var DataLong    = '';

    $(document).ready(function(){

        $('#sumber').on('change', function(){
            if(this.value == 'Data Leads')
            {
                $('.reqdataleads').css('display', 'inline-block')
                $('#dataleads').attr('readonly', false)
                $('#dataleads').attr('required', true)
            }
            else
            {
                $('.reqdataleads').css('display', 'none')
                $('#dataleads').attr('readonly', true)
                $('#dataleads').attr('required', false)
                $('#dataleads').val('')
            }
        })

        $('#indikasi').on('change', function(){
            if(this.value == 'Layanan Transaksi Lain')
            {
                $('.reqlayanantransaksilain').css('display', 'inline-block')
                $('#layanantransaksilain').attr('readonly', false)
                $('#layanantransaksilain').attr('required', true)
            }
            else
            {
                $('.reqlayanantransaksilain').css('display', 'none')
                $('#layanantransaksilain').attr('readonly', true)
                $('#layanantransaksilain').attr('required', false)
                $('#layanantransaksilain').val('')
            }
        })
    })

    function GetLocation()
    {
        navigator.permissions.query({
            name: 'geolocation'
        }).then(function(result) {
            if (result.state == 'granted') {
                // console.log(result.state);
                navigator.geolocation.getCurrentPosition(GetLatlong);
            } else if (result.state == 'prompt') {
                // console.log(result.state);
                navigator.geolocation.getCurrentPosition(GetLatlong);
            } else if (result.state == 'denied') {
                // console.log(result.state);
                Spandiv.LoadResources(Spandiv.Resources.sweetalert2, function() {
                    Swal.fire({
                        title: 'Akses Lokasi',
                        text: 'Kami membutuhkan ijin lokasi anda, silahkan beri ijin lokasi untuk aplikasi ini sesuai langkah dibawah',
                        icon: "warning",
                        allowOutsideClick: false,
                        confirmButtonText: "Cara Memberi Ijin Lokasi",
                        confirmButtonColor: "#3085d6"
                    }).then(function(){
                        window.open('https://support.google.com/chrome/answer/142065?hl=id', '_blank').focus();
                    });
                });
            }
            result.onchange = function() {
                // console.log(result.state);
            }
        });
    }

    function GetLatlong(data)
    {
        DataLat     = data.coords.latitude;
        DataLong    = data.coords.longitude;

        // DataLat     = -7.514271;
        // DataLong    = 110.516980;

        $('#latitude ').val(DataLat)
        $('#longitude').val(DataLong)
        var url     = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat="+DataLat+"&lon="+DataLong+""
        GetData(url, AfterGetLatlong)
    }

    function AfterGetLatlong(data)
    {
        swal.close()

        if(data.address.city == 'Special Capital Region of Jakarta')
        {
            $('#provinsi').val(data.address.city)
            $('#kota').val(data.address.city_district)
            $('#kecamatan').val(data.address.municipality)
            $('#desa').val(data.address.neighbourhood)
            $('#kodepos').val(data.address.postcode)
            $('#detail_alamat').val(data.display_name)
        }
        else if(data.address.state == 'Special Region of Yogyakarta')
        {
            $('#provinsi').val(data.address.state)
            $('#kota').val(data.address.county)
            $('#kecamatan').val(data.address.municipality)
            $('#desa').val(data.address.village)
            $('#kodepos').val(data.address.postcode)
            $('#detail_alamat').val(data.display_name)
        }
        else
        {
            $('#provinsi').val(data.address.state)
            $('#kota').val(data.address.city)
            $('#kecamatan').val(data.address.city_district != undefined ? data.address.city_district : data.address.municipality)
            $('#desa').val(data.address.village != undefined ? data.address.village : data.address.neighbourhood)
            $('#kodepos').val(data.address.postcode)
            $('#detail_alamat').val(data.display_name)
        }

        if(data.address.postcode != undefined || data.address.postcode != '')
        {
            if(data.address.city != 'Special Capital Region of Jakarta' && data.address.state != 'Special Region of Yogyakarta')
            {
                if($('#desa').val() == '')
                {
                    GetDataByCodePos(data.address.postcode, 'desa')
                }
                if($('#kecamatan').val() == '')
                {
                    GetDataByCodePos(data.address.postcode, 'kecamatan')
                }
                if($('#kota').val() == '')
                {
                    GetDataByCodePos(data.address.postcode, 'kota')
                }
                if($('#provinsi').val() == '')
                {
                    GetDataByCodePos(data.address.postcode, 'provinsi')
                }
            }
        }
    }

    function GetDataByCodePos(kodepos, finddata)
    {
        url = "{{url('solicit')}}";
        var datas = new FormData();
        datas.append('kodepos',kodepos);
        datas.append('finddata',finddata);
        datas.append('_token','{{csrf_token()}}');
        sendFormData(url+'/GetDataByCodePos',datas, AfterGetDataByCodePos);
    }

    function AfterGetDataByCodePos(data)
    {
        swal.close()
        if(data[data.finddata] != '')
        {
            $('#'+data.finddata).val(data[data.finddata])
        }
    }
</script>
@endsection
