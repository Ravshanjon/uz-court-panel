<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresidiumData extends Model
{
    protected $table = 'presidium_data';
    protected $fillable = ['appeal_id', 'date', 'reason_id', 'main_delta'];

    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }
}
