<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtName extends Model
{
    protected $fillable = ['name'];

    public function judges()
    {
        return $this->hasMany(Judges::class);
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'judge_id');
    }
}
