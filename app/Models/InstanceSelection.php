<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstanceSelection extends Model
{
    public $table = 'instancesselection';
    protected $fillable = ['name'];

    public function appeal()
    {
        return $this->hasMany(Appeal::class);
    }
}
