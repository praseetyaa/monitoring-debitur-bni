@extends('faturhelper::layouts/admin/main')

@section('title', 'Notifikasi')

@section('content')

    <div class="d-sm-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-2 mb-sm-0">Notifikasi</h1>
    </div>

    <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    @if(Auth::user()->role_id == 6)
                        @if(count($verifsolicit)>0)
                            <a onclick="verifisolicit()">
                                <div class="alert alert-success shadow" role="alert">
                                    <div class="alert-message d-flex align-items-center">
                                        <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($verifsolicit)}} Solicit Memerlukan verifikasi dari anda
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endif

                    @if(Auth::user()->role_id == 4)
                        @if(count($needprospek)>0)
                            <a onclick="needprospek()">
                                <div class="alert alert-success shadow" role="alert">
                                    <div class="alert-message d-flex align-items-center">
                                        <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($needprospek)}} Prospek memerlukan tindak lanjut dari anda
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if(count($needpipeline)>0)
                            <a onclick="needpipeline()">
                                <div class="alert alert-success shadow" role="alert">
                                    <div class="alert-message d-flex align-items-center">
                                        <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($needpipeline)}} Pipeline memerlukan tindak lanjut dari anda
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endif

                    @if(Auth::user()->role_id == 3)
                        @if(count($appsolicit)>0)
                            <a onclick="appisolicit()">
                                <div class="alert alert-success shadow" role="alert">
                                    <div class="alert-message d-flex align-items-center">
                                        <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($appsolicit)}} Solicit Memerlukan approval dari anda
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if(count($appprospek)>0)
                            <a onclick="appiprospek()">
                                <div class="alert alert-success shadow" role="alert">
                                    <div class="alert-message d-flex align-items-center">
                                        <i class="bi bi-bell-fill"></i>&nbsp;&nbsp;{{count($appprospek)}} Prospek Memerlukan approval dari anda
                                    </div>
                                </div>
                            </a>
                        @endif


                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- //////////////////////////////////////////// MODAL SHOW LIST //////////////////////////////////////////// --}}
    <div class="modal fade" id="ModalShowList" tabindex="-1" aria-labelledby="ModalShowListLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="ModalShowListLabel"></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <table class="table table-sm w-100 table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="nowrap hiddennotif" style="display: none;">Inputer</th>
                                        <th class="nowrap hiddennotif" style="display: none;">Npp</th>
                                        <th class="nowrap">Debitur</th>
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
            $('#ModalShowListLabel').html(`<i class="bi bi-bell"></i> Daftar Solicit Perlu Verifikasi`)
            $('.hiddennotif').css('display', '')
            var verifsolicit= @json($verifsolicit);
            var bodytable       = '';
            verifsolicit.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_input']+`</td>
                                    <td>`+val['npp_input']+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }

        function appisolicit()
        {
            $('#ModalShowListLabel').html(`<i class="bi bi-bell"></i> Daftar Solicit Perlu Approval`)
            $('.hiddennotif').css('display', '')
            var appsolicit= @json($appsolicit);
            var bodytable       = '';
            appsolicit.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_input']+`</td>
                                    <td>`+val['npp_input']+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }


        function appiprospek()
        {
            $('#ModalShowListLabel').html(`<i class="bi bi-bell"></i> Daftar Prospek Perlu Approval`)
            $('.hiddennotif').css('display', '')
            var appprospek= @json($appprospek);
            var bodytable       = '';
            appprospek.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_input']+`</td>
                                    <td>`+val['npp_input']+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }
        function needprospek()
        {
            $('#ModalShowListLabel').html(`<i class="bi bi-bell"></i> Daftar Prospek`)
            var needprospek= @json($needprospek);
            var bodytable       = '';
            needprospek.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
                                    <td class='text-center'>`+(i+1)+`</td>
                                    <td>`+val['nama_debitur']+`</td>
                                    <td>`+val['sektor']+`</td>
                                </tr>`
            });
            $('#BodyModalShowList').html(bodytable);
            $('#ModalShowList').modal('show');
        }

        function needpipeline()
        {
            $('#ModalShowListLabel').html(`<i class="bi bi-bell"></i> Daftar Pipeline`)
            var needpipeline= @json($needpipeline);
            var bodytable       = '';
            needpipeline.forEach(function(val, i){
                bodytable +=    `<tr style="cursor:pointer" onclick="OpenURL('datadebdetail/`+val['id']+`')">
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
    <style>
        .flex-even {
            flex: 1;
            height: 100%!important:
        }
    </style>
@endsection
