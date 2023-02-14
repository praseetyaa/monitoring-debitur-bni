<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttribute extends \Ajifatur\FaturHelper\Models\UserAttribute
{
    /**
     * Cabang.
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id')->withTrashed();
    }
    
    /**
     * Jabatan.
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id')->withTrashed();
    }

    /**
     * Tim.
     */
    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id')->withTrashed();
    }

    /**
     * Vendor.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Tipe.
     */
    public function tipe()
    {
        return $this->belongsTo(Tipe::class, 'tipe_id');
    }
}
