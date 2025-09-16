<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProceedingScore extends Model
{
    protected $fillable = ['proceeding_id','scope','penalty','reason'];
    public function proceeding() { return $this->belongsTo(Proceeding::class); }
}
