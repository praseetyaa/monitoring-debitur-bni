<html>
<head>
    <title>Laporan Monitoring ATM</title>
</head>
<body>
    <h1 class="fw-bold text-center">Laporan Monitoring ATM</h1>
    <p class="text-center">Dari tanggal {{ date('d/m/Y', strtotime($t1)) }} sampai dengan {{ date('d/m/Y', strtotime($t2)) }} <strong>{{ $cabang ? 'di cabang '.$cabang->nama : '' }}</strong></p>
    <table class="bordered">
        <thead class="bg-light">
            <tr>
                <th width="10">No.</th>
                <th width="50">Waktu</th>
                <th>Petugas</th>
                <th>NPP</th>
                <th>Vendor</th>
                <th>ATM</th>
                <th>Cabang</th>
                <th width="200">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @if(count($monitoring) > 0)
                @foreach($monitoring as $key=>$m)
                <tr>
                    <td align="right">{{ ($key+1) }}</td>
                    <td>
                        <div>{{ date('d/m/Y', strtotime($m->created_at)) }}</div>
                        <small>{{ date('H:i', strtotime($m->created_at)) }} WIB</small>
                    </td>
                    <td>{{ $m->user->name }}</td>
                    <td>{{ $m->user->role_id == role('pegawai') ? $m->user->attribute->npp : '-' }}</td>
                    <td>
                        @if($m->vendor)
                            <div>{{ $m->vendor->nama }}</div>
                            <small>{{ $m->tipe ? $m->tipe->nama : '' }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div>{{ $m->atm->nama }}</div>
                        <small>ID: {{ $m->atm->id_atm }}</small>
                    </td>
                    <td>{{ $m->cabang->nama }}</td>
                    <td>{{ $m->lokasi }}</td>
                </tr>
                @endforeach
            @else
                <tr><td align="center" colspan="8">Tidak ada data</td></tr>
            @endif
        </tbody>
    </table>
    <style>
        table.no-border td, table.no-border th{padding-bottom: 5px}
        table.bordered{border-collapse: collapse; width:100%}
        table.bordered, table.bordered td, table.bordered th{border:1px solid #000;}
        table.bordered td, table.bordered th{padding:10px}
        table td{vertical-align: top;}
        .mt-1{margin-top:1em}
        .mt-2{margin-top:2em}
        .p-1{padding:1em}
        .border{border:1px solid #000}
        .w-100{width:100%}
        .fw-bold{font-weight:bold}
        .text-center{text-align:center}
    </style>
</body>
</html>