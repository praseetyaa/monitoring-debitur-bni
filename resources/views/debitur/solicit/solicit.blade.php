@extends('faturhelper::layouts/admin/main')

@section('title', 'Data Solicit')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Data Solicit</h1>
    <div class="btn-group">
        <a href="{{ route('solicitcreate') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Input Solicit</a>
    </div>
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
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2" style="width: 1px;white-space:nowrap">No</th>
                                <th rowspan="2">Nama Deb/ Cal Deb</th>
                                <th colspan="2">Lokasi Usaha</th>
                                <th rowspan="2">Sektor</th>
                                <th rowspan="2">Bidang Usaha</th>
                                <th rowspan="2">Kategori</th>
                                <th rowspan="2">Orientasi Ekspor</th>
                                <th rowspan="2">Indikasi Kebutuhan Produk/Jasa</th>
                                <th rowspan="2">Sumber</th>
                                <th rowspan="2">Opsi</th>
                            </tr>
                            <tr>
                                <th>Alamat Detail</th>
                                <th>KodePos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index=>$a)
                            <tr>
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{ $a->nama_debitur }}</td>
                                <td>{{ $a->desa }}, {{ $a->kecamatan }}, {{ $a->kota }}, {{ $a->provinsi }} ({{ $a->detail_alamat }})</td>
                                <td>{{ $a->kodepos }}</td>
                                <td>{{ $a->sektor }}</td>
                                <td>{{ $a->bidang_usaha }}</td>
                                <td>{{ $a->kategori }}</td>
                                <td>{{ $a->orientasiekspor }}</td>
                                <td>{{ $a->indikasi_kebutuhan_produk }}</td>
                                <td>{{ $a->sumber }} {{($a->dataleads != '' ? '('.$a->dataleads.')' : '')}}</td>
                                <td class="text-center" style="white-space: nowrap">
                                    <a href="{{ route('sektoredit', ['id' => $a->id]) }}" class="btn btn-sm btn-warning ml-2" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $a->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('solicitdelete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable");

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
</script>

@endsection
