<?php

namespace App\Models;

use App\Filament\Resources\InspectionRelationManagerResource\RelationManagers\ServiceinspectionRelationManager;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = 'regions';

    protected $fillable = ['name'];

    public function judges()
    {
        return $this->hasMany(Judges::class);
    }

    public function district_types()
    {
        return $this->belongsToMany(CourtType::class, 'court_type_region');
    }

    public function service_inspections()
    {
        return $this->hasMany(ServiceinspectionRelationManager::class);
    }

    public function appeals()
    {
        return $this->hasMany(Appeal::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }


}
