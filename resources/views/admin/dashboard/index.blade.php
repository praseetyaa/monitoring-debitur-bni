@extends('faturhelper::layouts/admin/main')

@section('title', 'Dashboard')

@section('content')

@if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('admin-wilayah') || Auth::user()->role_id == role('admin-cabang') || Auth::user()->role_id == role('admin-vendor'))

<div class="d-sm-flex justify-content-center align-items-center">
    <form id="form-filter" class="d-lg-flex" method="get" action="">
        @if(Auth::user()->role->is_global == 1)
        <div class="mb-lg-0 mb-2">
            <select name="cabang" class="form-select form-select-sm" data-bs-toggle="tooltip" title="Pilih Cabang">
                <option value="0">Semua Cabang</option>
                @foreach($cabang as $c)
                <option value="{{ $c->id }}" {{ Request::query('cabang') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="ms-lg-2 ms-0 mb-lg-0 mb-2">
            <input type="text" id="t1" name="t1" class="form-control form-control-sm input-tanggal" value="{{ date('d/m/Y', strtotime($t1)) }}" autocomplete="off" data-bs-toggle="tooltip" title="Dari Tanggal">
        </div>
        <div class="ms-lg-2 ms-0 mb-lg-0 mb-2">
            <input type="text" id="t2" name="t2" class="form-control form-control-sm input-tanggal" value="{{ date('d/m/Y', strtotime($t2)) }}" autocomplete="off" data-bs-toggle="tooltip" title="Sampai Tanggal">
        </div>
        <div class="ms-lg-2 ms-0">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-filter-square me-1"></i> Filter</button>
        </div>
    </form>
</div>
<div class="alert alert-success mt-3" role="alert">
    <div class="alert-message">
        @if($t1 == date('Y-m-d') && $t2 == date('Y-m-d'))
            <p class="mb-0">Rekapitulasi data <strong>hari ini</strong>.</p>
        @else
            <p class="mb-0">Rekapitulasi data dari tanggal <strong>{{ date('d/m/Y', strtotime($t1)) }}</strong> sampai <strong>{{ date('d/m/Y', strtotime($t2)) }}</strong>.</p>
        @endif
    </div>
</div>
<hr class="my-3">
<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Interior</h1>
</div>
<div class="row">
    @foreach($interior as $i)
    <div class="col-lg-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h2 class="text-white">{{ $i->count }}</h2>
                <p class="card-text mb-0">{{ $i->awal }} {{ $i->is_reverse == 0 ? 'Tidak' : '' }} {{ $i->akhir }}</p>
            </div>
            <div class="card-footer py-1">
                <a href="{{ route('admin.monitoring.category', ['id' => $i->id, 'cabang' => Request::query('cabang'), 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}" class="small fw-bold">Lihat Selengkapnya &raquo;</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="d-sm-flex justify-content-between align-items-center my-3">
    <h1 class="h3 mb-0">Eksterior</h1>
</div>
<div class="row">
    @foreach($eksterior as $e)
    <div class="col-lg-3 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <h2 class="text-dark">{{ $e->count }}</h2>
                <p class="card-text mb-0">{{ $e->awal }} {{ $e->is_reverse == 0 ? 'Tidak' : '' }} {{ $e->akhir }}</p>
            </div>
            <div class="card-footer py-1">
                <a href="{{ route('admin.monitoring.category', ['id' => $e->id, 'cabang' => Request::query('cabang'), 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}" class="small fw-bold">Lihat Selengkapnya &raquo;</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@else

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Dashboard</h1>
</div>
<div class="alert alert-success" role="alert">
    <div class="alert-message d-flex align-items-center">
        @if(Auth::user()->avatar != '' && File::exists(public_path('assets/images/users/'.Auth::user()->avatar)))
            <img src="{{ asset('assets/images/users/'.Auth::user()->avatar) }}" class="img-fluid bg-white me-3 rounded-circle me-1" alt="{{ Auth::user()->name }}" width="70">
        @else
            <div class="avatar-letter rounded-circle me-3 bg-dark d-flex align-items-center justify-content-center" style="height:70px; width:70px">
                <h2 class="text-white mb-0">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</h2>
            </div>
        @endif
        <div>
            <h4 class="alert-heading">Selamat Datang!</h4>
            <p class="mb-0">Selamat datang kembali <strong>{{ Auth::user()->name }}</strong> di {{ config('app.name') }}.</p>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="fw-bold">Nama:</div>
                        <p class="mb-0">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="fw-bold">Username:</div>
                        <p class="mb-0">{{ Auth::user()->username }}</p>
                    </div>
                    @if(Auth::user()->role_id == role('pegawai'))
                    <div class="col-12 mb-3">
                        <div class="fw-bold">NPP:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->npp }}</p>
                    </div>
                    @endif
                    @if(Auth::user()->role_id == role('petugas'))
                    <div class="col-12 mb-3">
                        <div class="fw-bold">Nama Vendor:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->vendor->nama }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="fw-bold">Tipe Vendor:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->tipe->nama }}</p>
                    </div>
                    @endif
                    <div class="col-12">
                        <div class="fw-bold">Cabang:</div>
                        <p class="mb-0">{{ Auth::user()->attribute->cabang->nama }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@endsection

@section('js')

<script>
    // Datepicker
    Spandiv.DatePicker("input[name=t1], input[name=t2]");
</script>

@if(Session::get('status'))

<script>
    alertSuccess("Berhasil menambah data");
    function alertSuccess(text) {
        Spandiv.LoadResources(Spandiv.Resources.sweetalert2, function() {
            Swal.fire({
                text: text,
                icon: "success",
                allowOutsideClick: false,
                confirmButtonText: "OK",
                confirmButtonColor: "#3085d6"
            });
        });
    }
</script>

@endif

@endsection