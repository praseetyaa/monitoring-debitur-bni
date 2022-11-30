@extends('faturhelper::layouts/admin/main')

@section('title', 'Dashboard')

@section('content')


{{-- <div class="alert alert-success" role="alert">
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
</div> --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>: {{$user->name}}</td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td>: {{$user->username}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>: {{$user->email}}</td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>: {{@$user->role->name}}</td>
                    </tr>
                    <tr>
                        <td>NPP</td>
                        <td>: {{@$user->attribute->npp}}</td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon</td>
                        <td>: {{@$user->attribute->phone_number}}</td>
                    </tr>
                    <tr>
                        <td>Cabang</td>
                        <td>: {{@$user->attribute->cabang->nama}}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>: {{@$user->attribute->jabatan->nama}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

    {{-- //////////////////////////////////////////// NOTIFICATION //////////////////////////////////////////// --}}
        <div class="row">
            <div class="col-12">
                @if(Auth::user()->role_id == 6)
                    @if(count($verifsolicit)>0)
                        <a onclick="verifisolicit()">
                            <div class="alert alert-success" role="alert">
                                <div class="alert-message d-flex align-items-center">
                                    {{count($verifsolicit)}} Solicit Memerlukan verifikasi dari anda, klik disini untuk melihat
                                </div>
                            </div>
                        </a>
                    @endif
                @endif


                @if(Auth::user()->role_id == 3)
                    @if(count($appsolicit)>0)
                        <a onclick="appisolicit()">
                            <div class="alert alert-success" role="alert">
                                <div class="alert-message d-flex align-items-center">
                                    {{count($appsolicit)}} Solicit Memerlukan approval dari anda, klik disini untuk melihat
                                </div>
                            </div>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    {{-- //////////////////////////////////////////// MODAL SHOW LIST //////////////////////////////////////////// --}}
        <div class="modal fade" id="ModalShowList" tabindex="-1" aria-labelledby="ModalShowListLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="ModalShowListLabel"></h5>
                        <button type="button" class="btn btn-sm btn-primary" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <table class="table table-sm w-100 table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="nowrap">Nama Debitur</th>
                                            <th class="nowrap">sektor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="BodyModalShowList">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function verifisolicit()
            {
                $('#ModalShowListLabel').html('Daftar Solicit Perlu Verifikasi')
                var verifsolicit= @json($verifsolicit);
                var bodytable       = '';
                verifsolicit.forEach(function(val, i){
                    bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('solicit/solicitdetail/`+val['id']+`')">
                                        <td class='text-center'>`+(i+1)+`</td>
                                        <td>`+val['nama_debitur']+`</td>
                                        <td>`+val['sektor']+`</td>
                                    </tr>`
                });
                $('#BodyModalShowList').html(bodytable);
                $('#ModalShowList').modal('show');
            }

            function appisolicit()
            {
                $('#ModalShowListLabel').html('Daftar Solicit Perlu Approval')
                var appsolicit= @json($appsolicit);
                var bodytable       = '';
                appsolicit.forEach(function(val, i){
                    bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('solicit/solicitdetail/`+val['id']+`')">
                                        <td class='text-center'>`+(i+1)+`</td>
                                        <td>`+val['nama_debitur']+`</td>
                                        <td>`+val['sektor']+`</td>
                                    </tr>`
                });
                $('#BodyModalShowList').html(bodytable);
                $('#ModalShowList').modal('show');
            }

            function OpenURL(url)
            {
                var NewUrl = "<?= URL::to('"+url+"') ?>"
                window.location.href = NewUrl
            }
        </script>


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
