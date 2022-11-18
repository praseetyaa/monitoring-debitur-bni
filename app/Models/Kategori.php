<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'awal', 'akhir', 'opsi_ya', 'opsi_tidak', 'is_reverse', 'contoh'
    ];

    /**
     * Jenis.
     */
    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    /**
     * Tipe kategori.
     */
    public function tipe()
    {
        return $this->belongsToMany(Tipe::class, 'tbl_tipe__kategori', 'kategori_id', 'tipe_id');
    }
}
