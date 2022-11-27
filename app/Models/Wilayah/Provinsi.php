<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;
    protected $table = 'mst_dbprovinsi';
    protected $primary_key = 'id_provinsi';

    public function kota()
    {
        return $this->hasMany(Kota::class,'id_provinsi','id_provinsi');
    }

    public function kodepos()
    {
        return $this->hasMany(Kodepos::class,'id_kodepos','id_kodepos');
    }
}
