<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudgesRegistry extends Model
{
    protected $fillable = ['code', 'region_id', 'full_name', 'brith_day', 'judges_anouncment'];

    public function region()
    {
        return $this->belongsTo(Regions::class, 'region_id');
    }

}

