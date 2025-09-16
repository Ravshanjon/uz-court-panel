<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $fillable = ['name'];

    public function candidates()
    {
        return $this->hasMany(Candidates_document::class);
    }
}
