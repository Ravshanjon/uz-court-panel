<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateAward extends Model
{
    protected $table = 'private_awards';
    protected $fillable = [
        'date',
        'judge_id',
        'file',
        'name'
    ];

    public function judges()
    {
        return $this->hasMany(Judges::class, 'judges_id');
    }
}
