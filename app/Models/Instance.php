<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
    public $table = 'instances';
    protected $fillable = ['name'];

    public function reason()
    {
        return $this->hasMany(Reason::class);
    }

    public function appeal()
    {

        return $this->hasMany(Appeal::class);
    }

    public function appelationData()
    {

        return $this->hasMany(AppelationData::class);
    }


}
