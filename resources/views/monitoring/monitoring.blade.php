@extends('faturhelper::layouts/admin/main')

@section('title', 'Monitoring Pengguna')

@section('content')
    <style>
        .nowrap
        {
            white-space: nowrap!important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/Buttons-2.3.3/css/buttons.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/FixedColumns-4.2.1/css/fixedColumns.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/dttables')}}/datatablescustom.css"/>

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

                            <!-- PERLU DI TINJAU -->
                            @if (Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator'))
                            <select required id="cabang" class="form-select" disabled="">
                                @foreach($user as $index=>$a)
                                    <option value="{{ $a->attribute->cabang->id }}" {{$cabang == $a->attribute->cabang->id ? 'selected' : $a->attribute->cabang->id}}>{{ $a->attribute->cabang->nama }}</option>
                                    @foreach($DCabang as $c)
                                        <option value="{{ $a->attribute->cabang->id }}" {{$cabang == $a->attribute->cabang->id ? 'selected' : ''}}>{{ $c->nama }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            @else
                            <select required id="cabang" class="form-select">
                                <option value="" {{$cabang == '' ? 'selected' : ''}}>Semua Cabang</option>
                                @foreach($DCabang as $c)
                                    <option value="{{ $c->id }}" {{$cabang == $c->id ? 'selected' : ''}}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                            @endif
                            <!-- END -->

                        </div>
                        <div class="col-md-4">
                            <label class="mb-2" style="font-weight: bold">Unit</label>
                            <select required id="unit" class="form-select">
                                <option value="" {{$unit == '' ? 'selected' : ''}}>Semua Unit</option>
                                @foreach($DUnit as $c)
                                    <option value="{{ $c->id }}" {{$unit == $c->id ? 'selected' : ''}}>{{ $c->nama }}</option>
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
                        <div class="col-md-12 text-center">
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
                            var unit = $('#unit').val() != '' ?  $('#unit').val() : null

                            var NewUrl = "<?= URL::to('monitoring/"+cabang+"/"+unit+"/"+role+"') ?>"
                            window.location.href = NewUrl
                        }
                    </script>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="datatablexxx">
                            <thead class="bg-light">
                                <tr>
                                    <th width="30">No</th>
                                    <th class="nowrap">Nama</th>
                                    <th class="nowrap">Username</th>
                                    <th class="nowrap">Role</th>
                                    <th class="nowrap">Cabang</th>
                                    <th class="nowrap">Jabatan</th>
                                    <th class="nowrap">Total Input</th>
                                    <th class="nowrap">Verif Solicit</th>
                                    <th class="nowrap">Total Solicit</th>
                                    <th class="nowrap">Total Prospect</th>
                                    <th class="nowrap">Total Pipeline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user as $index=>$a)
                                <tr>
                                    <td class="text-center">{{$index+1}}</td>
                                    <td class="nowrap">{{ $a->name }}</td>
                                    <td class="nowrap">{{ $a->username }}</td>
                                    <td class="nowrap">{{ $a->role->name }}</td>
                                    <td class="nowrap">{{ $a->attribute->cabang->nama }}</td>
                                    <td class="nowrap">{{ $a->attribute->jabatan->nama }}</td>
                                    <td class="text-center nowrap">
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
                                    <td class="text-center nowrap">
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
                                    <td class="text-center nowrap">
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
                                    <td class="text-center nowrap">
                                        @if($a->dataapppros_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURL('{{ $a->id }}', '4')">
                                                {{$a->dataapppros_count}}
                                            </a>
                                        @else
                                            <a style="cursor: default;" class="btn btn-sm bg-secondary text-white">
                                                0
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center nowrap">
                                        @if($a->totalpipeline_count > 0)
                                            <a class="btn btn-sm bg-info text-white" onclick="OpenURL('{{ $a->id }}', '5')">
                                                {{$a->totalpipeline_count}}
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
    <script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>
    <script>
        $(document).ready(function(){
            setTimeout(function() {
                table = new DataTable('#datatablexxx', {
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    fixedColumns:
                    {
                        leftColumns: 0,
                        rightColumns:0
                    },
                    bFilter: true,
                    bInfo: true,
                    dom: 'Blfrtip',
                    responsive: false,
                    buttons: [
                        {
                            extend: 'excel',
                            className: 'exportbtn',
                        },
                        {
                            extend: 'pdf',
                            className: 'exportbtn',
                        },
                        {
                            extend: 'print',
                            className: 'exportbtn',
                        }

                    ]
                });
            }, 1000);
        })
    </script>

@endsection

@section('js')

    <script type="text/javascript" src="{{asset('/')}}jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/JSZip-2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/DataTables-1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/Buttons-2.3.3/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="{{asset('/dttables')}}/FixedColumns-4.2.1/js/dataTables.fixedColumns.min.js"></script>
    <script>
        function OpenURL(id, status)
        {
            var NewUrl = "<?= URL::to('daftarmonitoring/"+id+"/"+status+"') ?>"
            window.location.href = NewUrl
        }
    </script>

@endsection
