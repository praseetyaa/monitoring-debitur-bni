<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataDebitur extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'data_debitur';
    protected $primary_key = 'id';

    public function statusdebitur()
    {
        return $this->hasOne(StatusDebitur::class, 'status_debitur', 'status_debitur');
        // return $this->belongsTo(DataDebitur::class, 'status_debitur');
    }

    public function picinputer()
    {
        // return $this->hasOne(User::class, 'id', 'id_input');
        return $this->hasOne(User::class, 'id', 'id_input')->withTrashed();

    }
}
