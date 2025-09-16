<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $table = 'reasons';

    protected $fillable = [
        'type_of_decision_id',
        'instances_id',
        'reason',
        'score',
        'name'
    ];

    public function TypeOfDecision()
    {
        return $this->belongsTo(TypeOfDecision::class);
    }

    public function judge(){
        return $this->hasMany(Judges::class);
    }

    public function instances()
    {
        return $this->belongsTo(Instance::class);
    }
    public function appeal()
    {
        return $this->hasMany(Appeal::class);
    }
    public function appelationDate(){

        return $this->hasMany(AppelationData::class);
    }


}
