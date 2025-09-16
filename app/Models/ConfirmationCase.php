<?php

namespace App\Models;

use App\Filament\Pages\Desipline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ConfirmationCase extends Model
{
    protected $table = 'confirmation_of_cases';
    protected $fillable = ['name'];

    public function desipline()
    {
        return $this->hasMany(Desipline::class,'confirmation_of_cases_id');
    }

}
