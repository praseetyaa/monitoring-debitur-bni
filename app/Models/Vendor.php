<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_vendor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama'
    ];

    /**
     * ATM.
     */
    public function atm()
    {
        return $this->hasMany(ATM::class);
    }

    /**
     * Tipe vendor.
     */
    public function tipe()
    {
        return $this->belongsToMany(Tipe::class, 'tbl_tipe__vendor', 'vendor_id', 'tipe_id');
    }
}
