<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_monitoring';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'longitude', 'latitude', 'lokasi', 'catatan', 'img'
    ];

    /**
     * User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    /**
     * Cabang.
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    /**
     * ATM.
     */
    public function atm()
    {
        return $this->belongsTo(ATM::class, 'atm_id');
    }

    /**
     * Detail.
     */
    public function detail()
    {
        return $this->hasMany(MonitoringDetail::class);
    }
}
