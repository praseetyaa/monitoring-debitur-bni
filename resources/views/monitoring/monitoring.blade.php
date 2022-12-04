@extends('faturhelper::layouts/admin/main')

@section('title', 'Monitoring Pengguna')

@section('content')

    <div class="d-sm-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-2 mb-sm-0">Monitoring Pengguna</h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="mb-2" style="font-weight: bold">Cabang</label>
                            <select required id="cabang" class="form-select">
                                <option value="" {{$cabang == '' ? 'selected' : ''}}>Semua Cabang</option>
                                @foreach($DCabang as $c)
                                    <option value="{{ $c->id }}" {{$cabang == $c->id ? 'selected' : ''}}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-2" style="font-weight: bold">Role</label>
                            <select required id="role" class="form-select">
                                <option value="" {{$role == '' ? 'selected' : ''}}>Semua Role</option>
                                @foreach($DRoles as $c)
                                    <option value="{{ $c->id }}" {{$role == $c->id ? 'selected' : ''}}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 text-center">
                            <label class="mb-2" style="font-weight: bold">&nbsp;</label><br>
                            <a onclick="setfilter()" class="btn btn-sm btn-secondary mr-2"><i class="bi bi-filter-square"></i> Filter Data</a>
                            <a onclick="resetfilter()" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Reset Filter</a>
                        </div>
                    </div>
                    <script>
                        function resetfilter()
                        {
                            var NewUrl = "<?= URL::to('monitoring/') ?>"
                            window.location.href = NewUrl
                        }
                        function setfilter()
                        {
                            var role = $('#role').val() != '' ?  $('#role').val() : null
                            var cabang = $('#cabang').val() != '' ?  $('#cabang').val() : null

                            var NewUrl = "<?= URL::to('monitoring/"+cabang+"/"+role+"') ?>"
                            window.location.href = NewUrl
                        }
                    </script>
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
                                    <th>Input Solicit</th>
                                    <th>Verif Solicit</th>
                                    <th>Approve Solicit</th>
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
                                    <td class="text-center">
                                        @if($a->datainput_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURL('{{ $a->id }}', '1')">
                                                {{$a->datainput_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($a->dataverif_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURL('{{ $a->id }}', '2')">
                                                {{$a->dataverif_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($a->dataapp_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURL('{{ $a->id }}', '3')">
                                                {{$a->dataapp_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
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
    <script>
        function OpenURL(id, status)
        {
            var NewUrl = "<?= URL::to('daftarmonitoring/"+id+"/"+status+"') ?>"
            window.location.href = NewUrl
        }
    </script>

@endsection
