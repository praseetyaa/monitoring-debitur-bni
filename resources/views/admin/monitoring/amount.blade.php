@extends('faturhelper::layouts/admin/main')

@section('title', 'Jumlah Monitoring')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Jumlah Monitoring</h1>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Nama:</div>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>
                    @if($user->role_id == role('pegawai'))
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">NPP:</div>
                        <p class="mb-0">{{ $user->attribute->npp }}</p>
                    </div>
                    @endif
                    @if($user->role_id == role('petugas'))
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Nama Vendor:</div>
                        <p class="mb-0">{{ $user->attribute->vendor->nama }}</p>
                    </div>
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Tipe Vendor:</div>
                        <p class="mb-0">{{ $user->attribute->tipe->nama }}</p>
                    </div>
                    @endif
                    <div class="col-6 col-md mb-3 mb-md-0">
                        <div class="fw-bold">Cabang:</div>
                        <p class="mb-0">{{ $user->attribute->cabang->nama }}</p>
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
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th width="100">Tanggal</th>
                                <th width="100">Hari</th>
                                <th width="60">Jumlah Monitoring</th>
                                <th>ATM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monitoring as $key=>$m)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($key)) }}</td>
                                <td>{{ $days[date('w', strtotime($key))] }}</td>
                                <td>{{ $m }}</td>
                                <td>
                                    @foreach($list[$key] as $l)
                                        {{ $l->atm->nama }}
                                    @endforeach
                                    <div class="small">ID: {{ $l->atm->id_atm }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')

<script>
    // DataTable
    Spandiv.DataTable("#datatable", {
        orderAll: true
    });
</script>

@endsection