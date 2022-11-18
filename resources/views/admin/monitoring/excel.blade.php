<table border="1">
    <thead>
        <tr>
            <th width="5">No.</th>
            <th width="15">Tanggal</th>
            <th width="15">Jam</th>
            <th width="25">Nama Petugas</th>
            <th width="25">NPP</th>
            <th width="25">Nama Vendor</th>
            <th width="25">Tipe Vendor</th>
            <th width="25">ID ATM</th>
            <th width="25">Nama ATM</th>
            <th width="25">Cabang</th>
            <th width="50">Lokasi</th>
            @foreach($kategori as $k)
            <th width="25">{{ $k->awal }} {{ $k->akhir }}</th>
            @endforeach
            <th width="25">Catatan</th>
        </tr>
    </thead>
    <tbody>
        @if(count($monitoring) > 0)
            @foreach($monitoring as $key=>$m)
            <tr>
                <td align="right">{{ ($key+1) }}</td>
                <td>{{ date('d/m/Y', strtotime($m->created_at)) }}</td>
                <td>{{ date('H:i', strtotime($m->created_at)) }}</td>
                <td>{{ $m->user->name }}</td>
                <td>{{ $m->user->role_id == role('pegawai') ? $m->user->attribute->npp : '-' }}</td>
                <td>{{ $m->vendor ? $m->vendor->nama : '-'}}</td>
                <td>{{ $m->vendor && $m->tipe ? $m->tipe->nama : '-' }}</td>
                <td>{{ $m->atm->id_atm }}</td>
                <td>{{ $m->atm->nama }}</td>
                <td>{{ $m->cabang->nama }}</td>
                <td>{{ $m->lokasi }}</td>
                @foreach($kategori as $k)
                    <?php $detail = $m->detail()->where('kategori_id','=',$k->id)->first(); ?>
                    @if($detail)
                        @if($detail->jawaban == 1)
                            <td>{{ $k->opsi_ya != '' ? $k->opsi_ya : 'Ya' }}</td>
                        @else
                            <td>{{ $k->opsi_tidak != '' ? $k->opsi_tidak : 'Tidak' }}</td>
                        @endif
                    @else
                        <td>-</td>
                    @endif
                @endforeach
                <td>{{ $m->catatan }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>