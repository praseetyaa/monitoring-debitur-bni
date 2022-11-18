<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_tipe';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama'
    ];

    /**
     * Tipe vendor.
     */
    public function vendor()
    {
        return $this->belongsToMany(Vendor::class, 'tbl_tipe__vendor', 'tipe_id', 'vendor_id');
    }

    /**
     * Tipe kategori.
     */
    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'tbl_tipe__kategori', 'tipe_id', 'kategori_id');
    }
}
