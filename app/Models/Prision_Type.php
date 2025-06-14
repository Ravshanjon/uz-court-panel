<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prision_Type extends Model
{
    protected $table = 'prision_type';

    protected $fillable =
        [
            'name',
            'score'
        ];

    public function serviceinspection()
    {
        return $this->hasMany(service_inspection::class,'prision_type_id');
    }

}
