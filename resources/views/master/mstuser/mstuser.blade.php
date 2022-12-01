@extends('faturhelper::layouts/admin/main')

@section('title', 'Manajemen Pengguna')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Manajemen Pengguna</h1>
    @if(Auth::user()->role_id != role('monitoring'))
    <div class="btn-group">
        <a href="{{ route('piccreate', ['role'=>$role]) }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Pengguna</a>
    </div>
    @endif
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
                                <th width="30">No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Cabang</th>
                                <th>Jabatan</th>
                                @if(Auth::user()->role_id != role('monitoring'))
                                <th width="60">Opsi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user as $index=>$a)
                            <tr>
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{ $a->name }}</td>
                                <td>{{ $a->username }}</td>
                                <td>{{ $a->role->name }}</td>
                                <td>{{ $a->attribute->cabang->nama }}</td>
                                <td>{{ $a->attribute->jabatan->nama }}</td>
                                @if(Auth::user()->role_id != role('monitoring'))
                                <td class="text-center" style="white-space: nowrap">
                                    <a href="{{ route('picedit', ['id' => $a->id]) }}" class="btn btn-sm btn-warning ml-2" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $a->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>

<form class="form-delete d-none" method="post" action="{{ route('picdelete') }}">
    @csrf
    <input type="hidden" name="id">
    <input type="hidden" name="routename" value="{{Request::route()->getName()}}">
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
