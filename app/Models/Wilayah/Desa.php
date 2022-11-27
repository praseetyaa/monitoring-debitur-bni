<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;
    protected $table = 'mst_dbdesa';
    protected $primary_key = 'id_desa';

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function kodepos()
    {
        return $this->hasMany(Kodepos::class,'id_kodepos','id_kodepos');
    }
}
