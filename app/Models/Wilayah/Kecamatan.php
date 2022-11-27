<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $table = 'mst_dbkecamatan';
    protected $primary_key = 'id_kecamatan';

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function desa()
    {
        return $this->hasMany(Desa::class,'id_desa','id_desa');
    }

    public function kodepos()
    {
        return $this->hasMany(Kodepos::class,'id_kodepos','id_kodepos');
    }
}
