@extends('faturhelper::layouts/admin/main')

@section('title', 'Master Tim')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Master Tim</h1>
    @if(Auth::user()->role_id != role('monitoring'))
    <div class="btn-group">
        <a href="{{ route('timcreate') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Data</a>
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
                                <th>Tim</th>
                                @if(Auth::user()->role_id != role('monitoring'))
                                    <th width="60">Opsi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index=>$a)
                            <tr>
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{ $a->nama }}</td>
                                @if(Auth::user()->role_id != role('monitoring'))
                                <td class="text-center" style="white-space: nowrap">
                                    <a href="{{ route('timedit', ['id' => $a->id]) }}" class="btn btn-sm btn-warning ml-2" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('timdelete') }}">
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
