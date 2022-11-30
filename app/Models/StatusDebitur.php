<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusDebitur extends Model
{
    use HasFactory;
    protected $table = 'mst_status_debitur';
    protected $primary_key = 'id';

    public function datadebitur()
    {
        return $this->belongsTo(DataDebitur::class, 'status_debitur');
        // return $this->hasOne(StatusDebitur::class, 'status_debitur');
    }
}
