@extends('faturhelper::layouts/admin/main')

@section('title', 'Edit Solicit')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0" style="text-transform: capitalize">Edit Solicit</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('solicitupdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="mb-2" style="font-weight: bold">Nama Debitur<span class="text-danger">*</span></label>
                            <input required placeholder="Nama Debitur" type="text" name="nama_debitur" id="nama_debitur" class="form-control {{ $errors->has('nama_debitur') ? 'border-danger' : '' }}" value="{{ old('nama_debitur') }}" autofocus>
                            @if($errors->has('nama_debitur'))
                                <div class="small text-danger">{{ $errors->first('nama_debitur') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Bidang Usaha<span class="text-danger">*</span></label>
                            <input required placeholder="Bidang Usaha" type="text" name="bidang_usaha" id="bidang_usaha" class="form-control {{ $errors->has('bidang_usaha') ? 'border-danger' : '' }}" value="{{ old('bidang_usaha') }}" autofocus>
                            @if($errors->has('bidang_usaha'))
                                <div class="small text-danger">{{ $errors->first('bidang_usaha') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Sektor<span class="text-danger">*</span></label>
                            <select required name="sektor" id="sektor" class="form-select {{ $errors->has('sektor') ? 'border-danger' : '' }}">
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
                            <select required name="kategori" id="kategori" class="form-select {{ $errors->has('kategori') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Kategori--</option>
                                <option value="Pemain Utama">Pemain Utama</option>
                                <option value="Pemain Non Utama">Pemain Non Utama</option>
                            </select>
                            @if($errors->has('kategori'))
                            <div class="small text-danger">{{ $errors->first('kategori') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Orientasi Ekspor<span class="text-danger">*</span></label>
                            <select required name="orientasiekspor" id="orientasiekspor" class="form-select {{ $errors->has('orientasiekspor') ? 'border-danger' : '' }}">
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
                            <select required name="indikasi_kebutuhan_produk" id="indikasi_kebutuhan_produk" class="form-select {{ $errors->has('indikasi_kebutuhan_produk') ? 'border-danger' : '' }}">
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
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <a target="_blank" href="{{ route('openfile', ['path' => $data->dokumen_lokasi]) }}" class="btn btn-sm btn-primary w-100">Foto Lokasi</a>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="mb-2" style="font-weight: bold">Foto Lokasi<span class="text-danger" style="display: none">*</span></label>
                                <input type="file" class="form-control" name="foto_lokasi" accept="image/*" capture="camera">
                                <small>*Silahkan pilih file jika ingin memperbaharui</small>
                            </div>
                        </div>
                    <hr>
                    {{-- <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Provinsi<span class="text-danger">*</span></label>
                            <select required name="provinsi" id="provinsi" class="form-select {{ $errors->has('provinsi') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Provinsi--</option>
                                @foreach($Provinsi as $c)
                                <option value="{{ $c->id_provinsi }}_{{ $c->nama_provinsi }}" {{ old('provinsi') == $c->id_provinsi ? 'selected' : '' }}>{{ $c->nama_provinsi }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('provinsi'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kabupaten/Kota<span class="text-danger">*</span></label>
                            <select required name="kota" id="kota" class="form-select {{ $errors->has('kota') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Kabupaten/Kota--</option>
                            </select>
                            @if($errors->has('kota'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kecamatan<span class="text-danger">*</span></label>
                            <select required name="kecamatan" id="kecamatan" class="form-select {{ $errors->has('kecamatan') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Kecamatan--</option>
                            </select>
                            @if($errors->has('kecamatan'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Desa<span class="text-danger">*</span></label>
                            <select required name="desa" id="desa" class="form-select {{ $errors->has('desa') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Desa--</option>
                            </select>
                            @if($errors->has('desa'))
                            <div class="small text-danger">{{ $errors->first('desa') }}</div>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kode Pos<span class="text-danger">*</span></label>
                            <select required name="kodepos" id="kodepos" class="form-select {{ $errors->has('kodepos') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih Kode Pos--</option>
                            </select>
                            @if($errors->has('provinsi'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Detail Alamat</label>
                            <textarea placeholder="RT/RW, Jalan, Nomor Rumah" name="detail_alamat" id="detail_alamat" class="form-control {{ $errors->has('detail_alamat') ? 'border-danger' : '' }}" value="{{ old('detail_alamat') }}" autofocus></textarea>
                            @if($errors->has('detail_alamat'))
                                <div class="small text-danger">{{ $errors->first('detail_alamat') }}</div>
                            @endif
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            <a class="btn btn-primary btn-block" onclick="GetLocation()">Ambil Data lokasi</a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Latitude<span class="text-danger">*</span></label>
                            <input readonly placeholder="Latitude" type="text" name="latitude" id="latitude" class="form-control {{ $errors->has('latitude') ? 'border-danger' : '' }}" value="{{ old('latitude') }}" autofocus>
                            @if($errors->has('latitude'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Longitude<span class="text-danger">*</span></label>
                            <input readonly placeholder="Longitude" type="text" name="longitude" id="longitude" class="form-control {{ $errors->has('longitude') ? 'border-danger' : '' }}" value="{{ old('longitude') }}" autofocus>
                            @if($errors->has('longitude'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Provinsi<span class="text-danger">*</span></label>
                            <input readonly placeholder="Provinsi" type="text" name="provinsi" id="provinsi" class="form-control {{ $errors->has('provinsi') ? 'border-danger' : '' }}" value="{{ old('provinsi') }}" autofocus>
                            @if($errors->has('provinsi'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kabupaten/Kota<span class="text-danger">*</span></label>
                            <input readonly placeholder="Kabupaten/Kota" type="text" name="kota" id="kota" class="form-control {{ $errors->has('kota') ? 'border-danger' : '' }}" value="{{ old('kota') }}" autofocus>
                            @if($errors->has('kota'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kecamatan<span class="text-danger">*</span></label>
                            <input readonly placeholder="Kecamatan" type="text" name="kecamatan" id="kecamatan" class="form-control {{ $errors->has('kecamatan') ? 'border-danger' : '' }}" value="{{ old('kecamatan') }}" autofocus>
                            @if($errors->has('kecamatan'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Desa<span class="text-danger">*</span></label>
                            <input readonly placeholder="Desa" type="text" name="desa" id="desa" class="form-control {{ $errors->has('desa') ? 'border-danger' : '' }}" value="{{ old('desa') }}" autofocus>
                            @if($errors->has('desa'))
                            <div class="small text-danger">{{ $errors->first('desa') }}</div>
                            @endif
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Kode Pos<span class="text-danger">*</span></label>
                            <input readonly placeholder="Kode Pos" type="text" name="kodepos" id="kodepos" class="form-control {{ $errors->has('kodepos') ? 'border-danger' : '' }}" value="{{ old('kodepos') }}" autofocus>
                            @if($errors->has('provinsi'))
                            <div class="small text-danger">{{ $errors->first('sektor') }}</div>
                            @endif
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="mb-2"  style="font-weight: bold">Detail Alamat</label>
                            <textarea placeholder="RT/RW, Jalan, Nomor Rumah" name="detail_alamat" id="detail_alamat" class="form-control {{ $errors->has('detail_alamat') ? 'border-danger' : '' }}" value="{{ old('detail_alamat') }}" autofocus></textarea>
                            @if($errors->has('detail_alamat'))
                                <div class="small text-danger">{{ $errors->first('detail_alamat') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-2 text-center">
                            <button type="submit" class="btn btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('solicit') }}" class="btn btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
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
    datadebitur = @json($data);

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

        // $('#provinsi').val(datadebitur.id_provinsi+'_'+datadebitur.provinsi)
        // GetChild('provinsi', datadebitur.id_provinsi, true)
        // GetChild('kota', datadebitur.id_kota, true)
        // GetChild('kecamatan', datadebitur.id_kecamatan, true)
        // GetChild('desa', datadebitur.id_desa, true)

        $('#nama_debitur').val(datadebitur.nama_debitur)
        $('#latitude ').val(datadebitur.latitude)
        $('#longitude').val(datadebitur.longitude)
        $('#provinsi').val(datadebitur.provinsi)
        $('#kota').val(datadebitur.kota)
        $('#kecamatan').val(datadebitur.kecamatan)
        $('#desa').val(datadebitur.desa)
        $('#kodepos').val(datadebitur.kodepos)
        $('#detail_alamat').val(datadebitur.detail_alamat)
        $('#sektor').val(datadebitur.sektor)
        $('#bidang_usaha').val(datadebitur.bidang_usaha)
        $('#kategori').val(datadebitur.kategori)
        $('#orientasiekspor').val(datadebitur.orientasiekspor)
        $('#indikasi_kebutuhan_produk').val(datadebitur.indikasi_kebutuhan_produk)
        $('#sumber').val(datadebitur.sumber)
        $('#dataleads').val(datadebitur.dataleads)
        if(datadebitur.sumber == 'Data Leads')
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


        // $('#provinsi').on('change', function(){
        //     GetChild('provinsi', this.value, false)
        // })
        // $('#kota').on('change', function(){
        //     GetChild('kota', this.value, false)
        // })
        // $('#kecamatan').on('change', function(){
        //     GetChild('kecamatan', this.value, false)
        // })
        // $('#desa').on('change', function(){
        //     GetChild('desa', this.value, false)
        // })
        // $('#sumber').on('change', function(){
        //     if(this.value == 'Data Leads')
        //     {
        //         $('.reqdataleads').css('display', 'inline-block')
        //         $('#dataleads').attr('disabled', false)
        //         $('#dataleads').attr('required', true)
        //     }
        //     else
        //     {
        //         $('.reqdataleads').css('display', 'none')
        //         $('#dataleads').attr('disabled', true)
        //         $('#dataleads').attr('required', false)
        //     }
        // })
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

        $('#latitude ').val(DataLat)
        $('#longitude').val(DataLong)
        var url     = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat="+DataLat+"&lon="+DataLong+""
        GetData(url, AfterGetLatlong)
    }

    function AfterGetLatlong(data)
    {
        swal.close()
        $('#provinsi').val(data.address.state)
        $('#kota').val(data.address.city)
        $('#kecamatan').val(data.address.city_district)
        $('#desa').val(data.address.village)
        $('#kodepos').val(data.address.postcode)
        $('#detail_alamat').val(data.display_name)
    }


    function GetChild(parent, idparent, isload)
    {
        url = "{{url('solicit')}}";
        var datas = new FormData();
        datas.append('parent',parent);
        datas.append('idparent',idparent);
        datas.append('_token','{{csrf_token()}}');
        if(isload)
        {
            sendFormData(url+'/getchilddata',datas,AfterGetChild2);
        }
        else
        {
            sendFormData(url+'/getchilddata',datas,AfterGetChild);
        }
    }

    function AfterGetChild2(data)
    {
        Swal.close()

        if(data.parent == 'provinsi')
        {
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectkota' value='"+element.id_kota+"_"+element.nama_kota+"' {{ old('kota') == "+element.id_kota+" ? 'selected' : '' }}>"+element.nama_kota+"</option>"
            });
            $('#kota').append(datas)
            $('#kota').val(datadebitur.id_kota+'_'+datadebitur.kota)
        }
        else if(data.parent == 'kota')
        {
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectkecamatan' value='"+element.id_kecamatan+"_"+element.nama_kecamatan+"' {{ old('kecamatan') == "+element.id_kecamatan+" ? 'selected' : '' }}>"+element.nama_kecamatan+"</option>"
            });
            $('#kecamatan').append(datas)
            $('#kecamatan').val(datadebitur.id_kecamatan+'_'+datadebitur.kecamatan)
        }
        else if(data.parent == 'kecamatan')
        {
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectdesa' value='"+element.id_desa+"_"+element.nama_desa+"' {{ old('desa') == "+element.id_desa+" ? 'selected' : '' }}>"+element.nama_desa+"</option>"
            });
            $('#desa').append(datas)
            $('#desa').val(datadebitur.id_desa+'_'+datadebitur.desa)
        }
        else if(data.parent == 'desa')
        {
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectkodepos' value='"+element.id_kodepos+"_"+element.kodepos+"' {{ old('kodepos') == "+element.id_kodepos+" ? 'selected' : '' }}>"+element.kodepos+"</option>"
            });
            $('#kodepos').append(datas)
            $('#kodepos').val(datadebitur.id_kodepos+'_'+datadebitur.kodepos)
        }
    }

    function AfterGetChild(data)
    {
        Swal.close()
        if(data.parent == 'provinsi')
        {
            $('.selectkota').remove();
            $('.selectkecamatan').remove();
            $('.selectdesa').remove();
            $('.selectkodepos').remove();
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectkota' value='"+element.id_kota+"_"+element.nama_kota+"' {{ old('kota') == "+element.id_kota+" ? 'selected' : '' }}>"+element.nama_kota+"</option>"
            });
            $('#kota').append(datas)
            $('#kota').val('')
            $('#kecamatan').val('')
            $('#desa').val('')
            $('#kodepos').val('')
        }
        else if(data.parent == 'kota')
        {
            $('.selectkecamatan').remove();
            $('.selectdesa').remove();
            $('.selectkodepos').remove();
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectkecamatan' value='"+element.id_kecamatan+"_"+element.nama_kecamatan+"' {{ old('kecamatan') == "+element.id_kecamatan+" ? 'selected' : '' }}>"+element.nama_kecamatan+"</option>"
            });
            $('#kecamatan').append(datas)
            $('#kecamatan').val('')
            $('#desa').val('')
            $('#kodepos').val('')
        }
        else if(data.parent == 'kecamatan')
        {
            $('.selectdesa').remove();
            $('.selectkodepos').remove();
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectdesa' value='"+element.id_desa+"_"+element.nama_desa+"' {{ old('desa') == "+element.id_desa+" ? 'selected' : '' }}>"+element.nama_desa+"</option>"
            });
            $('#desa').append(datas)
            $('#desa').val('')
            $('#kodepos').val('')
        }
        else if(data.parent == 'desa')
        {
            $('.selectkodepos').remove();
            var datas = ''
            data.data.forEach(element => {
                datas += "<option class='selectkodepos' value='"+element.id_kodepos+"_"+element.kodepos+"' {{ old('kodepos') == "+element.id_kodepos+" ? 'selected' : '' }}>"+element.kodepos+"</option>"
            });
            $('#kodepos').append(datas)
            $('#kodepos').val('')
        }
    }
</script>
@endsection
