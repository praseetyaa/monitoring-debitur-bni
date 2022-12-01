<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kodepos extends Model
{
    use HasFactory;
    protected $table = 'mst_dbkodepos';
    protected $primary_key = 'id_kodepos';

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id_desa');
    }
}
