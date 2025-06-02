<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = ['name'];

    public function judges()
    {
        return $this->belongsTo(Judges::class);
    }
}
