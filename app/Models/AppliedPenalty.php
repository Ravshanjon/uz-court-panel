<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppliedPenalty extends Model
{
    protected $fillable = [
        'proceeding_id','root_first_id','judge_id','role_context',
        'amount','effective_from','effective_until','voided'
    ];
    protected $casts = [
        'effective_from'=>'datetime','effective_until'=>'datetime','voided'=>'bool'
    ];

    public function proceeding() { return $this->belongsTo(Proceeding::class); }
    public function judge()      { return $this->belongsTo(Judges::class); }

    // active scope (muddati o‘tmagan + voided=false)
    public function scopeActive($q) {
        return $q->where('voided', false)
            ->where(function($qq){
                $qq->whereNull('effective_until')
                    ->orWhere('effective_until','>', now());
            });
    }
}
