@extends('faturhelper::layouts/admin/main')

@section('title', 'Dashboard')

@section('content')


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