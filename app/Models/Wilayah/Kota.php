<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;
    protected $table = 'mst_dbkota';
    protected $primary_key = 'id_kota';

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class,'id_kecamatan','id_kecamatan');
    }

    public function kodepos()
    {
        return $this->hasMany(Kodepos::class,'id_kodepos','id_kodepos');
    }
}
